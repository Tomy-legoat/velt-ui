# Issue 05 - Definir contrats Component Renderer View

## Labels

`module:1-foundations`, `area:ui`, `type:architecture`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Stabiliser les contrats internes du moteur UI pour que WebRenderer, JsonRenderer, HTTP et Preview utilisent une API publique claire.

## Contrats a creer

- `ComponentInterface`
- `RendererInterface`
- `ViewInterface` ou equivalent
- `ComponentTreeInterface` si necessaire

## Travail attendu

- Definir comment un composant expose son type, ses props et ses enfants.
- Definir `RendererInterface::render(...)`.
- Definir comment une page devient tableau serialisable.
- Documenter quelles parties sont publiques et quelles parties sont internes.

## Criteres d'acceptation

- `Page`, `Text`, `Button`, `Form` implementent ou respectent les contrats.
- WebRenderer et JsonRenderer utilisent les contrats, pas des details prives.
- Preview peut serialiser une page sans connaitre chaque classe concrete.

## Definition of Done

- Contrats crees.
- Tests avec composant fake.
- README UI mis a jour.

