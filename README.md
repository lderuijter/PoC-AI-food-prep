# AI Food Prep – Aanbevelingssysteem

Voor dit project heb ik een eenvoudig machine-learning aanbevelingssysteem gemaakt dat recepten aanbeveelt op basis van de voorkeuren van een gebruiker.

De gebruiker geeft ingrediënten een beoordeling van **0 tot 5**. Deze beoordelingen worden in Python omgezet naar waarden tussen 0 en 1. Hierdoor kan het systeem de voorkeuren van de gebruiker vergelijken met de ingrediënten van recepten.

Voor het vergelijken van de gegevens gebruik ik **cosine similarity** uit `scikit-learn`. Hiermee wordt berekend hoe goed de ingrediënten van een recept overeenkomen met de voorkeuren van de gebruiker.

Naast de cosine similarity wordt ook gekeken naar de gemiddelde beoordeling van de ingrediënten die in een recept voorkomen. Deze twee scores worden gecombineerd tot één eindscore. De cosine similarity telt voor 40% mee en de directe voorkeuren voor 60%.

De recepten worden vervolgens op basis van deze score gesorteerd. Het beste gevonden recept krijgt een matchpercentage van 100%. De andere recepten worden relatief aan dit resultaat weergegeven.

Daarnaast berekent het systeem de gemiddelde **NOVA-groep** van ieder recept. Deze informatie wordt apart weergegeven en heeft geen invloed op de aanbevelingsscore.

## Gebruikte technieken

* Python
* NumPy
* Pandas
* Scikit-learn
* SQLite
* Cosine similarity

## Globale werking

Het systeem haalt eerst de recepten en de voorkeuren van de gebruiker uit de SQLite-database. Daarna worden de voorkeuren en recepten omgezet naar numerieke vectoren. Met cosine similarity wordt de overeenkomst berekend. Vervolgens wordt deze score gecombineerd met de directe voorkeuren voor de ingrediënten van het recept. De recepten worden daarna gesorteerd en de beste resultaten worden teruggegeven aan de Laravel-app.

Het doel van deze PoC is om met een relatief eenvoudige machine-learning techniek persoonlijke receptaanbevelingen te kunnen maken.
