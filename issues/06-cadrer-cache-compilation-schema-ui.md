# Issue 06 - Cadrer cache compilation et schema UI

## Labels

`module:1-foundations`, `area:ui`, `area:performance`, `type:architecture`, `priority:p1`, `status:ready`

## Objectif

Definir la direction future du cache UI sans implementer une compilation avancee dans le Module 1.

## Travail attendu

- Versionner le schema JSON preview.
- Documenter ce qui peut etre cache : page chargee, arbre serialise, HTML rendu.
- Definir les invalidations simples : fichier modifie, debug mode, production mode.
- Noter les sujets reportes : hydration, hot reload, build assets, compilation avancee.

## Criteres d'acceptation

- Le JSON contient `schemaVersion`.
- Les limites du cache Module 1 sont claires.
- Les modules Preview et UI utilisent le meme vocabulaire.

## Definition of Done

- Decision d'architecture documentee.
- Tests JSON schemaVersion ajoutes.
- Pas de cache complexe implemente par accident.

