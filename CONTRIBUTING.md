# Contributing to Velt UI

Merci de contribuer a Velt UI. Ce package est une brique fondatrice du framework Velt : il doit rester petit, stable, testable et clair.

## Objectif du package

Velt UI sert a :

- declarer des pages avec `Page` et des composants PHP ;
- rendre ces pages en HTML ;
- rendre ces pages en JSON Preview versionne ;
- charger des vues `.velt.php` ;
- exposer des contrats publics utilisables par le kernel, HTTP et Preview.

Velt UI ne doit pas gerer :

- le routing ;
- les controleurs ;
- la session ;
- les middlewares ;
- les assets frontend ;
- l'hydration ;
- le hot reload ;
- la compilation avancee.

Ces sujets appartiennent a d'autres modules Velt.

## Prerequis

- PHP 8.2 ou plus recent
- Composer

Installation locale :

```bash
composer install
composer dump-autoload
```

Tests :

```bash
vendor/bin/phpunit
```

Sur Windows :

```powershell
vendor\bin\phpunit
```

## Structure du projet

```text
src/
  Components/     Composants declaratifs MVP
  Contracts/      Contrats publics
  Renderers/      Renderers HTML et JSON Preview
  Support/        Helpers internes
  View/           Chargement des vues .velt.php
tests/            Tests PHPUnit
docs/             Documentation projet
issues/           Specifications de travail par issue
```

## Conventions de code

- Utiliser `declare(strict_types=1);`.
- Utiliser le namespace `Velt\Ui`.
- Garder les classes petites et explicites.
- Preferer les methodes publiques chainables pour l'API declarative.
- Ne pas ajouter de dependance externe sans raison forte.
- Ne pas introduire de logique metier dans les renderers.
- Ne pas melanger HTML dans le JSON Preview.
- Documenter les decisions non evidentes avec des commentaires courts.

## Renderers

`WebRenderer` doit :

- produire du HTML stable ;
- echapper les textes et attributs ;
- respecter les classes declarees ;
- generer les metas de page ;
- deleguer CSRF a la couche HTTP/session quand elle existe ;
- ne jamais generer un faux token silencieux.

`JsonRenderer` doit :

- produire un JSON stable ;
- inclure `schemaVersion` ;
- conserver les props logiques comme `variant`, `type`, `showIf` ;
- ne pas evaluer `showIf` ;
- ne pas inclure de HTML.

## Contrats publics

Les modules externes doivent dependres des contrats publics plutot que des details internes :

- `ComponentInterface`
- `RendererInterface`
- `ViewInterface`

Toute modification de contrat doit etre traitee comme une modification importante. Ajouter un champ au JSON Preview doit etre fait avec prudence. Changer ou supprimer un champ existant doit normalement impliquer une nouvelle version de schema.

## Tests attendus

Toute contribution fonctionnelle doit ajouter ou mettre a jour les tests.

Types de tests attendus :

- composants : structure `toArray()` ;
- HTML : snapshots ou assertions de fragments stables ;
- JSON Preview : structure decodee avec `schemaVersion` ;
- vues : filesystem temporaire et erreurs claires ;
- contrats : composant fake si la contribution touche les interfaces.

Avant de proposer un changement :

```bash
composer dump-autoload
vendor/bin/phpunit
```

## Documentation

Mettre a jour la documentation quand une contribution change :

- l'API publique ;
- le schema JSON Preview ;
- le mapping HTML ;
- le chargement des vues ;
- l'integration avec le kernel ;
- les limites du Module 1.

La documentation principale se trouve dans :

```text
docs/README.md
```

## Workflow recommande

1. Lire l'issue ou la specification concernee.
2. Identifier la surface publique impactee.
3. Implementer le changement le plus petit possible.
4. Ajouter les tests.
5. Mettre a jour la documentation.
6. Lancer la suite de tests.
7. Verifier qu'aucun comportement hors perimetre n'a ete ajoute.

## Regles d'architecture

- Une vue `.velt.php` retourne une `Page`.
- Le kernel choisit le renderer.
- Le kernel fournit les services runtime comme CSRF.
- Velt UI reste une bibliotheque pure.
- Le cache avance est reporte hors Module 1.
- Les composants doivent rester declaratifs.

## Proposer un nouveau composant

Un nouveau composant doit definir :

- son besoin reel ;
- son type interne ;
- ses props publiques ;
- son mapping HTML ;
- son mapping JSON Preview ;
- ses tests ;
- son exemple de documentation.

Eviter les composants trop specifiques tant que le framework n'a pas un besoin clair.

## Securite

Points importants :

- echapper toute sortie HTML ;
- refuser les chemins dangereux dans `ViewFactory` ;
- ne pas creer de token CSRF factice ;
- ne pas executer de controleur depuis une vue ;
- ne pas evaluer les expressions `showIf` dans le Module 1.

## Signalement de bugs

Un bon rapport de bug contient :

- la version ou le commit utilise ;
- le code minimal qui reproduit le probleme ;
- la sortie attendue ;
- la sortie obtenue ;
- l'environnement PHP et Composer.

## Licence

En contribuant, vous acceptez que votre contribution soit distribuee sous la licence du projet.
