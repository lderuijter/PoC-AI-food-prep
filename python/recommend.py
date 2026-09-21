import json
import sqlite3
import sys
from pathlib import Path

import numpy as np
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

# Bepaal de hoofdmap van het project en waar de SQLite-database staat.
BASE_DIR = Path(__file__).resolve().parent.parent
DB_PATH = BASE_DIR / "database" / "database.sqlite"


# Haalt alle recepten uit de database op.
def laad_recepten() -> pd.DataFrame:
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql("SELECT id, naam FROM recepten", conn)


# Haalt alle ingrediënten van recepten op.
# Hierbij wordt ook de NOVA-groep van ieder ingrediënt opgehaald.
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


# Haalt de door de gebruiker gegeven voorkeuren op.
def laad_voorkeuren(user_id: int) -> pd.DataFrame:
    with sqlite3.connect(DB_PATH) as conn:
        return pd.read_sql(
            "SELECT ingredient_id, voorkeur FROM ingredient_voorkeuren WHERE user_id = ?",
            conn,
            params=(user_id,),
        )


# Bepaalt welke kleur bij het matchpercentage hoort.
def bepaal_match_kleur(match_percentage: int) -> str:
    """Zet het matchpercentage om naar een kleurcode voor de UI."""
    if match_percentage >= 80:
        return "groen"
    if match_percentage >= 60:
        return "geel"
    if match_percentage >= 40:
        return "oranje"
    return "rood"


# Bepaalt welke kleur bij de gemiddelde NOVA-groep hoort.
def bepaal_kleur(gemiddelde_nova: float) -> str:
    """Zet de gemiddelde NOVA-groep van een recept om naar een kleurcode voor de UI."""
    if gemiddelde_nova < 1.5:
        return "groen"
    if gemiddelde_nova < 2.5:
        return "geel"
    if gemiddelde_nova < 3.5:
        return "oranje"
    return "rood"


# Maakt de persoonlijke aanbevelingen voor een gebruiker.
def aanbevelingen(user_id: int, top_n: int = 10) -> pd.DataFrame:
    # Haal alle benodigde gegevens uit de database.
    recepten = laad_recepten()
    recept_ingredienten = laad_recept_ingredienten()
    voorkeuren = laad_voorkeuren(user_id)

    # Zonder voorkeuren kunnen er geen persoonlijke aanbevelingen worden gemaakt.
    if voorkeuren.empty:
        raise ValueError("Deze gebruiker heeft nog geen voorkeuren.")

    # Maak een lijst van alle ingrediënten die in recepten voorkomen.
    # Elk ingrediënt krijgt een vaste positie in de vectoren.
    alle_ingredient_ids = sorted(recept_ingredienten["ingredient_id"].unique())
    index = {ingredient_id: i for i, ingredient_id in enumerate(alle_ingredient_ids)}

    # Maak de vector van de gebruiker.
    # Een hogere voorkeur krijgt een zwaardere waarde door het kwadrateren.
    # Hierdoor heeft een 5/5 veel meer invloed dan bijvoorbeeld een 2/5.
    gebruiker_vector = np.zeros(len(alle_ingredient_ids))
    for _, rij in voorkeuren.iterrows():
        if rij["ingredient_id"] in index:
            voorkeur = rij["voorkeur"]
            gebruiker_vector[index[rij["ingredient_id"]]] = (voorkeur / 5) ** 2

    # Maak voor ieder recept een vector.
    # 1 betekent dat het ingrediënt in het recept zit, 0 dat dit niet zo is.
    recept_vectoren = np.zeros((len(recepten), len(alle_ingredient_ids)))
    gemiddelde_nova_per_recept = {}

    for i, recept_id in enumerate(recepten["id"]):
        rijen = recept_ingredienten[recept_ingredienten["recept_id"] == recept_id]

        for ingredient_id in rijen["ingredient_id"]:
            if ingredient_id in index:
                recept_vectoren[i, index[ingredient_id]] = 1

        # Bereken de gemiddelde NOVA-groep van het recept.
        gemiddelde_nova_per_recept[recept_id] = rijen["nova_groep"].mean()

    # Gebruik alleen ingrediënten waarvoor de gebruiker een positieve beoordeling heeft.
    # Onbekende ingrediënten worden daardoor niet meegenomen in de vergelijking.
    beoordeelde_ingredienten = gebruiker_vector > 0

    # Bereken met scikit-learn hoe sterk de gebruiker en ieder recept overeenkomen.
    # Hoe dichter de vectoren bij elkaar passen, hoe hoger de similarity-score.
    scores = cosine_similarity(
        gebruiker_vector[beoordeelde_ingredienten].reshape(1, -1),
        recept_vectoren[:, beoordeelde_ingredienten],
    )[0]

    # Maak een nieuw DataFrame met de recepten en hun berekende scores.
    resultaat = recepten.copy()
    resultaat["score"] = scores

    # Koppel de gemiddelde NOVA-groep aan ieder recept.
    resultaat["gemiddelde_nova"] = resultaat["id"].map(gemiddelde_nova_per_recept)

    # Bepaal de kleur van de NOVA-groep voor de website.
    resultaat["nova_kleur"] = resultaat["gemiddelde_nova"].apply(bepaal_kleur)

    # Recepten zonder overeenkomst met beoordeelde ingrediënten worden niet getoond.
    resultaat = resultaat[resultaat["score"] > 0]

    if resultaat.empty:
        return resultaat

    # Zet de similarity-score om naar een percentage.
    # Het best scorende recept krijgt hierbij 100%.
    resultaat["match_percentage"] = (
        resultaat["score"] / resultaat["score"].max() * 100
    ).round().astype(int)

    # Bepaal de kleur van het matchpercentage.
    resultaat["match_kleur"] = resultaat["match_percentage"].apply(bepaal_match_kleur)

    # Zet de beste matches bovenaan.
    resultaat = resultaat.sort_values("score", ascending=False)

    # Geef alleen de gegevens terug die de website nodig heeft.
    return resultaat.head(top_n)[
        [
            "naam",
            "score",
            "match_percentage",
            "match_kleur",
            "gemiddelde_nova",
            "nova_kleur",
        ]
    ]


# Dit stuk wordt uitgevoerd wanneer dit Python-bestand direct wordt gestart.
if __name__ == "__main__":
    # Gebruik het user-ID dat vanuit Laravel wordt meegegeven.
    # Als er geen ID wordt meegegeven, wordt gebruiker 1 gebruikt.
    user_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1

    try:
        # Bereken de aanbevelingen en geef ze terug als JSON.
        resultaat = aanbevelingen(user_id)
        print(resultaat.to_json(orient="records"))
    except ValueError as e:
        # Geef eventuele foutmeldingen terug als JSON.
        print(json.dumps({"error": str(e)}))
