# Sous-module 04 - UI Rendering

## Mission

Ce sous-module est le coeur distinctif de Velt. Il fournit la syntaxe declarative officielle en PHP, construit un arbre UI en memoire, puis le rend soit en HTML pour le Web, soit en JSON pour la preview mobile.

## Perimetre

Inclus :

- `Page`
- composants `Text`, `Button`, `Card`, `Form`, `Input`, `Link`, `Alert`
- props chainables ;
- rendu HTML MVP ;
- rendu JSON MVP ;
- tests snapshot simples.

Exclus :

- compilation type React/Svelte ;
- Tailwind runtime ;
- etat interactif avance ;
- hot reload.

## Issues

- [Issue 01 - Creer Page et composants UI](issues/01-creer-page-composants-ui.md)
- [Issue 02 - Implementer WebRenderer HTML](issues/02-implementer-web-renderer-html.md)
- [Issue 03 - Implementer JsonRenderer preview](issues/03-implementer-json-renderer-preview.md)
- [Issue 04 - Ajouter ViewFactory et chargement des pages Velt](issues/04-ajouter-viewfactory-chargement-pages-velt.md)
