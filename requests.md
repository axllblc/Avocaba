# Liste des requêtes

## Recherche de magasins

- Recherche par code postal

```SQL
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`CodePos` = 37200
LIMIT 10;
```

- Recherche par département

```SQL
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.CodeDep = '37'
LIMIT 10;
```

- Recherche par nom de ville

```SQL
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`Slug` LIKE 'tours'
LIMIT 10;
```
