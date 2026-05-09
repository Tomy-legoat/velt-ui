# Issue 02 - Implementer WebRenderer HTML

## Labels

`module:1-foundations`, `area:ui`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Transformer une page Velt declarative en HTML utilisable dans un navigateur.

## Mapping MVP

| Composant | HTML attendu |
| --- | --- |
| `Page` | document HTML complet ou fragment selon option |
| `Card` | `section` |
| `Text` | `p`, `h1`, `h2`, etc. selon `as()` |
| `Alert` | `div role="alert"` |
| `Form` | `form` |
| `Input` | `label` + `input` |
| `Button` | `button` |
| `Link` | `a` |

## Travail attendu

- Creer `WebRenderer`.
- Echappe correctement le texte et les attributs.
- Appliquer les classes telles quelles.
- Generer les metas de page.
- Detecter l'intention `csrf()` et deleguer la generation du vrai champ au contrat HTTP/session quand il est disponible.

## Contraintes

- Ne pas executer de logique metier dans le renderer.
- Ne pas inclure Tailwind automatiquement dans cette issue, sauf option documentee.
- Proteger contre l'injection HTML basique via escaping.

## Criteres d'acceptation

- Une page Connexion produit du HTML valide.
- `meta.title` devient `<title>`.
- `Input::required()` devient un attribut `required`.
- `Form::csrf()` ne genere pas un faux token silencieux ; le comportement sans session est documente.
- Les snapshots HTML sont stables.

## Definition of Done

- Renderer implemente.
- Tests snapshot.
- Documentation du mapping.
