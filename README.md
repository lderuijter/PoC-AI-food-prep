# AI Food Prep – Aanbevelingssysteem

Voor dit project heb ik een eenvoudig aanbevelingssysteem gemaakt dat recepten aanbeveelt op basis van de voorkeuren van een gebruiker.

De gebruiker kan ingrediënten een beoordeling van **0 tot 5** geven. Deze beoordelingen worden in Python omgezet naar waardes die gebruikt kunnen worden om recepten met elkaar te vergelijken.

Met **cosine similarity** uit `scikit-learn` wordt gekeken hoe goed de ingrediënten van een recept overeenkomen met de voorkeuren van de gebruiker. Recepten met ingrediënten die de gebruiker hoog heeft beoordeeld komen daardoor hoger in de resultaten.

De resultaten worden gesorteerd op basis van deze overeenkomst. Het beste resultaat krijgt een matchpercentage van 100% en de overige resultaten worden hier relatief aan weergegeven.

Daarnaast wordt voor ieder recept de gemiddelde **NOVA-groep** berekend. Deze wordt los van de aanbevelingsscore weergegeven.

## Gebruikte technieken

* Python
* NumPy
* Pandas
* Scikit-learn
* SQLite
* Cosine similarity

## Globale werking

```text
Voorkeuren gebruiker
        ↓
Voorkeuren omzetten naar waardes
        ↓
Recepten en voorkeuren vergelijken
        ↓
Cosine similarity berekenen
        ↓
Resultaten sorteren
        ↓
Aanbevelingen tonen
```

Het doel van deze PoC is om met een relatief simpele machine-learning techniek persoonlijke receptaanbevelingen te kunnen maken.
