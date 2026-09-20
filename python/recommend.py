import json
import sqlite3
import sys
from pathlib import Path

import numpy as np
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

BASE_DIR = Path(__file__).resolve().parent.parent
DB_PATH = BASE_DIR / "database" / "database.sqlite"


def laad_recepten() -> pd.DataFrame:
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql("SELECT id, naam FROM recepten", conn)


def laad_recept_ingredienten() -> pd.DataFrame:
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql(
            """
            SELECT ri.recept_id, ri.ingredient_id, i.nova_groep
            FROM recept_ingredienten ri
            JOIN ingredienten i ON i.id = ri.ingredient_id
            """,
            conn,
        )


def laad_voorkeuren(user_id: int) -> pd.DataFrame:
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql(
            "SELECT ingredient_id, voorkeur FROM ingredient_voorkeuren WHERE user_id = ?",
            conn,
            params=(user_id,),
        )

def bepaal_match_kleur(match_percentage: int) -> str:
    """Zet het matchpercentage om naar een kleurcode voor de UI."""
    if match_percentage >= 80:
        return "groen"
    if match_percentage >= 60:
        return "geel"
    if match_percentage >= 40:
        return "oranje"
    return "rood"

def bepaal_kleur(gemiddelde_nova: float) -> str:
    """Zet de gemiddelde NOVA-groep van een recept om naar een kleurcode voor de UI."""
    if gemiddelde_nova < 1.5:
        return "groen"
    if gemiddelde_nova < 2.5:
        return "geel"
    if gemiddelde_nova < 3.5:
        return "oranje"
    return "rood"


def aanbevelingen(user_id: int, top_n: int = 10) -> pd.DataFrame:
    recepten = laad_recepten()
    recept_ingredienten = laad_recept_ingredienten()
    voorkeuren = laad_voorkeuren(user_id)

    if voorkeuren.empty:
        raise ValueError("Deze gebruiker heeft nog geen voorkeuren.")

    alle_ingredient_ids = sorted(recept_ingredienten["ingredient_id"].unique())
    index = {ingredient_id: i for i, ingredient_id in enumerate(alle_ingredient_ids)}

    gebruiker_vector = np.zeros(len(alle_ingredient_ids))
    for _, rij in voorkeuren.iterrows():
        if rij["ingredient_id"] in index:
            gebruiker_vector[index[rij["ingredient_id"]]] = rij["voorkeur"]

    recept_vectoren = np.zeros((len(recepten), len(alle_ingredient_ids)))
    gemiddelde_nova_per_recept = {}

    for i, recept_id in enumerate(recepten["id"]):
        rijen = recept_ingredienten[recept_ingredienten["recept_id"] == recept_id]
        for ingredient_id in rijen["ingredient_id"]:
            if ingredient_id in index:
                recept_vectoren[i, index[ingredient_id]] = 1
        gemiddelde_nova_per_recept[recept_id] = rijen["nova_groep"].mean()

    scores = cosine_similarity(gebruiker_vector.reshape(1, -1), recept_vectoren)[0]

    resultaat = recepten.copy()
    resultaat["score"] = scores
    resultaat["gemiddelde_nova"] = resultaat["id"].map(gemiddelde_nova_per_recept)
    resultaat["nova_kleur"] = resultaat["gemiddelde_nova"].apply(bepaal_kleur)

    # Cold start: recepten zonder enige overlap (score 0) niet tonen
    resultaat = resultaat[resultaat["score"] > 0]

    if resultaat.empty:
        return resultaat

    # Percentage relatief aan de beste match in dit resultaat
    resultaat["match_percentage"] = (
        resultaat["score"] / resultaat["score"].max() * 100
    ).round().astype(int)

    resultaat["match_kleur"] = resultaat["match_percentage"].apply(bepaal_match_kleur)

    resultaat = resultaat.sort_values("score", ascending=False)

    return resultaat.head(top_n)[["naam", "score", "match_percentage", "match_kleur", "gemiddelde_nova", "nova_kleur"]]


if __name__ == "__main__":
    user_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1
    try:
        resultaat = aanbevelingen(user_id)
        print(resultaat.to_json(orient="records"))
    except ValueError as e:
        print(json.dumps({"error": str(e)}))
