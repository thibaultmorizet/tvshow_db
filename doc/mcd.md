```
EPISODE: number, title, duration
BELONGS TO, 11 EPISODE, 1N SEASON
SEASON: number, year
HAS, 1N SHOW, 11 SEASON

CATEGORY: label
RELATED TO, 1N SHOW, 0N CATEGORY
SHOW: title, synopsis, release_date
DIRECTED BY, 11 SHOW, 0N PERSON
PERSON: first_name, last_name, birth_date, gender, country

APPEARS IN, 11 CHARACTER, 0N SHOW
CHARACTER: name
INTERPRETED BY, 1N CHARACTER, 0N PERSON
```