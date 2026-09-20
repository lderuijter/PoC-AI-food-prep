import json
import sqlite3
import sys
from pathlib import Path

import numpy as np
import pandas as pd
from sklearn.neighbors import NearestNeighbors
from sklearn.preprocessing import StandardScaler

# Pad naar de sqlite-database van de Laravel-app (twee mappen omhoog vanaf dit script)
BASE_DIR = Path(__file__).resolve().parent.parent
DB_PATH = BASE_DIR / "database" / "database.sqlite"


def laad_ingredienten() -> pd.DataFrame:
    """Haalt alle rijen uit de ingredienten-tabel op als DataFrame."""
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql("SELECT * FROM ingredienten", conn)


def laad_voorkeuren(user_id: int) -> pd.DataFrame:
    """Haalt de opgeslagen voorkeuren (1-5) van één gebruiker op."""
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql(
            "SELECT ingredient_id, voorkeur FROM ingredient_voorkeuren WHERE user_id = ?",
            conn,
            params=(user_id,),
        )


def bouw_features(df: pd.DataFrame) -> np.ndarray:
    """
    Zet de ruwe ingredient-data om in een numerieke featurematrix:
    - macro's/calorieën/NOVA worden geschaald (StandardScaler) zodat grote getallen
      (bv. calorieën) niet zwaarder wegen dan kleine (bv. NOVA-groep 1-4)
    - categorie + smaakprofiel worden one-hot encoded (elke waarde wordt een eigen 0/1-kolom)
    """
    numeriek = df[[
        "calorieen_per_100g",
        "eiwitten_per_100g",
        "koolhydraten_per_100g",
        "vetten_per_100g",
        "nova_groep",
    ]].fillna(0)

    categorisch = pd.get_dummies(df[["categorie", "smaakprofiel"]].fillna("onbekend"))

    matrix = pd.concat(
        [pd.DataFrame(StandardScaler().fit_transform(numeriek), columns=numeriek.columns), categorisch],
        axis=1,
    )
    return matrix.to_numpy()


def aanbevelingen(user_id: int, top_n: int = 10) -> pd.DataFrame:
    """
    Kernlogica:
    1. Pak alle ingrediënten die de gebruiker goed vindt (voorkeur >= 4).
    2. Zoek voor elk daarvan de dichtstbijzijnde buren (cosine similarity op de features).
    3. Tel de "dichtbij-scores" op per kandidaat-ingrediënt (zo kan iets met meerdere
       favoriete buren hoger scoren).
    4. Filter alles wat de gebruiker al beoordeeld heeft eruit — dit zijn juist de
       nieuwe/nog-niet-geziene suggesties.
    5. Sorteer op score, met eiwitten/NOVA als tiebreaker (sporters-prioriteit).
    """
    ingredienten = laad_ingredienten()
    voorkeuren = laad_voorkeuren(user_id)

    if voorkeuren.empty:
        raise ValueError("Deze gebruiker heeft nog geen voorkeuren.")

    features = bouw_features(ingredienten)

    # NearestNeighbors: onbegeleid (unsupervised) model, geen labels nodig,
    # zoekt puur op gelijkenis tussen featurevectoren.
    model = NearestNeighbors(n_neighbors=min(top_n + 1, len(ingredienten)), metric="cosine")
    model.fit(features)

    geliefd = voorkeuren[voorkeuren["voorkeur"] >= 4]["ingredient_id"].tolist()
    beoordeeld = set(voorkeuren["ingredient_id"])

    scores: dict[int, float] = {}
    for ingredient_id in geliefd:
        idx = ingredienten.index[ingredienten["id"] == ingredient_id]
        if idx.empty:
            continue
        afstanden, buren = model.kneighbors(features[idx[0]].reshape(1, -1))
        for afstand, buur_idx in zip(afstanden[0], buren[0]):
            buur_id = ingredienten.iloc[buur_idx]["id"]
            if buur_id in beoordeeld:
                continue  # al beoordeeld, dus geen "nieuwe" suggestie
            # cosine-afstand -> gelijkenis (1 - afstand), opgeteld bij bestaande score
            scores[buur_id] = scores.get(buur_id, 0) + (1 - afstand)

    if not scores:
        return pd.DataFrame()

    resultaat = ingredienten[ingredienten["id"].isin(scores.keys())].copy()
    resultaat["score"] = resultaat["id"].map(scores)

    # Sporters-prioriteit als tiebreaker: bij gelijke score wint hoger eiwit / lagere NOVA-groep
    resultaat = resultaat.sort_values(
        by=["score", "eiwitten_per_100g", "nova_groep"],
        ascending=[False, False, True],
    )

    return resultaat.head(top_n)[["naam", "categorie", "eiwitten_per_100g", "nova_groep", "score"]]


if __name__ == "__main__":
    # Wordt aangeroepen als: python3 recommend.py <user_id>
    # Print JSON naar stdout, zodat Laravel (via Process::run) het kan decoden.
    user_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1
    try:
        resultaat = aanbevelingen(user_id)
        print(resultaat.to_json(orient="records"))
    except ValueError as e:
        print(json.dumps({"error": str(e)}))
