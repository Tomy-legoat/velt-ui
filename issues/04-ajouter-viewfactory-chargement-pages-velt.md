# Issue 04 - Ajouter ViewFactory et chargement des pages Velt

## Labels

`module:1-foundations`, `area:ui`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Permettre de charger une page Velt depuis un fichier PHP declaratif.

## Pourquoi cette issue est obligatoire

Les composants et renderers ne suffisent pas. Le framework doit pouvoir faire quelque chose comme `view('auth.login')` et charger `resources/views/auth/login.velt.php` ou un chemin equivalent.

## Travail attendu

- Creer `ViewFactory`.
- Creer `ViewNotFoundException`.
- Supporter une racine de vues configurable.
- Charger un fichier `.velt.php`.
- Verifier que le fichier retourne une instance de `Page`.
- Exposer `make(string $name): Page`.

## Contraintes

- Ne pas executer de controleur dans un fichier de vue.
- Ne pas autoriser de chemins dangereux comme `../`.
- Ne pas imposer un dossier final unique si le skeleton Module 2 le precise plus tard.

## Criteres d'acceptation

- `view('auth.login')` charge le bon fichier.
- Une vue absente lance `ViewNotFoundException`.
- Une vue qui ne retourne pas `Page` lance une erreur claire.
- La page chargee peut etre rendue en HTML.
- La page chargee peut etre rendue en JSON.

## Definition of Done

- `ViewFactory` implemente.
- Tests filesystem avec vues factices.
- Documentation avec exemple `view('auth.login')`.

