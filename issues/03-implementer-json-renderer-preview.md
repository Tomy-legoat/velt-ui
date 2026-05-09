# Issue 03 - Implementer JsonRenderer preview

## Labels

`module:1-foundations`, `area:ui`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Transformer une page Velt en JSON stable pour l'application mobile de preview.

## Exemple cible

```json
{
  "screen": "Connexion",
  "layout": "auth",
  "meta": {
    "title": "Connexion - Velt App"
  },
  "components": [
    {
      "type": "Card",
      "props": {
        "class": "p-8"
      },
      "children": []
    }
  ]
}
```

## Travail attendu

- Creer `JsonRenderer`.
- Serialiser `Page`, props et children.
- Garder les variantes logiques comme `variant`, `type`, `showIf`.
- Ajouter une version de schema JSON, par exemple `schemaVersion: 1`.

## Contraintes

- Ne pas inclure de HTML dans le JSON.
- Ne pas evaluer `showIf` dans le MVP.
- Le JSON doit rester stable pour que l'app mobile puisse le consommer.

## Criteres d'acceptation

- Une page Connexion produit un JSON valide.
- Tous les composants MVP sont representes.
- Les props utiles sont conservees.
- Les tests valident la structure JSON.

## Definition of Done

- Renderer JSON implemente.
- Tests de structure.
- Schema documente.

