# Connexion velt-ui au kernel

Ce document decrit la connexion qui a ete faite entre `velt-ui` et le kernel local `veltphp-kernel`.

## Structure locale

Les deux projets sont dans le meme dossier parent :

```text
C:\Users\HP\Dev\03_PROJECTS\active
+-- velt-ui
+-- veltphp-kernel
    +-- packages
        +-- kernel
```

`velt-ui` reste le package UI autonome. Le kernel consomme ce package via Composer.

## Connexion Composer

Dans `veltphp-kernel/packages/kernel/composer.json`, le kernel declare une dependance vers `velt/ui` :

```json
"require": {
    "php": "^8.2",
    "velt/ui": "*"
}
```

Comme les deux packages sont locaux, le kernel utilise un repository Composer de type `path` :

```json
"repositories": [
    {
        "type": "path",
        "url": "../../../velt-ui",
        "options": {
            "symlink": true
        }
    }
]
```

Cela permet au kernel de charger directement le code de `velt-ui` sans publier le package.

Commande lancee cote kernel :

```powershell
cd ..\veltphp-kernel\packages\kernel
composer update
```

Composer a ensuite installe `velt/ui` comme lien local vers `../../../velt-ui`.

## Provider ajoute dans le kernel

Un provider a ete ajoute dans le kernel :

```text
veltphp-kernel/packages/kernel/src/Ui/UiServiceProvider.php
```

Son role est d'enregistrer les services UI dans le container du kernel :

```php
Velt\Ui\View\ViewFactory::class
Velt\Ui\Renderers\WebRenderer::class
Velt\Ui\Renderers\JsonRenderer::class
```

Il ajoute aussi ces alias :

```php
view
ui.renderer.web
ui.renderer.json
```

## Chemin des vues

Par defaut, le provider cherche les vues dans :

```text
{basePath}/resources/views
```

Donc une vue logique :

```php
$views->make('auth.login');
```

pointe vers :

```text
resources/views/auth/login.velt.php
```

Le chemin peut etre configure dans l'application kernel :

```php
$app = new Application(
    $basePath,
    [
        'view' => [
            'path' => $customViewPath,
        ],
    ]
);
```

## Flux teste

Le test d'integration cree une vraie vue `.velt.php`, puis le kernel :

1. enregistre `UiServiceProvider` ;
2. recupere `ViewFactory` depuis le container ;
3. charge la vue `auth.login` ;
4. rend la page en HTML avec `WebRenderer` ;
5. rend la page en JSON Preview avec `JsonRenderer`.

Le test est dans :

```text
veltphp-kernel/packages/kernel/tests/UiServiceProviderTest.php
```

## Commandes de test

Depuis `velt-ui`, tester seulement UI :

```powershell
composer test
```

Depuis `velt-ui`, tester le kernel connecte a UI :

```powershell
cd ..\veltphp-kernel\packages\kernel
composer test
```

Pour afficher les deprecations PHPUnit de `velt-ui` :

```powershell
vendor\bin\phpunit --display-phpunit-deprecations
```

## Resultat obtenu

Kernel :

```text
OK (74 tests, 95 assertions)
```

UI :

```text
OK, 36 tests, 95 assertions
```

`velt-ui` signale une depreciation PHPUnit sur le schema de `phpunit.xml`. Ce n'est pas une erreur de connexion kernel/UI.

## Important

La dependance va dans le sens kernel vers UI :

```text
veltphp-kernel -> velt-ui
```

`velt-ui` ne depend pas du kernel. C'est volontaire pour garder `velt-ui` testable comme bibliotheque pure.
