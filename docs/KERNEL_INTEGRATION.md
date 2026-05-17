# Kernel Integration

Ce document decrit comment le kernel Velt doit connecter `velt/ui` dans un projet complet.

`velt/ui` reste un package autonome. Le kernel l'utilise, mais ne doit pas copier sa logique.

## Role de velt/ui

`velt/ui` fournit :

- `Page` ;
- les composants declaratifs MVP ;
- `ViewFactory` pour charger les fichiers `.velt.php` ;
- `WebRenderer` pour produire du HTML ;
- `JsonRenderer` pour produire le JSON Preview ;
- les contrats publics `ComponentInterface`, `RendererInterface`, `ViewInterface`.

`velt/ui` ne fournit pas :

- routeur ;
- controleur ;
- request HTTP ;
- response HTTP ;
- container ;
- session ;
- stockage CSRF ;
- middleware ;
- gestion d'erreurs globale ;
- detection automatique du mode Preview.

Ces responsabilites appartiennent au kernel ou aux modules HTTP.

## Services a enregistrer dans le kernel

Le kernel peut enregistrer ces services dans son container.

### ViewFactory

```php
use Velt\Ui\View\ViewFactory;

$container->set(ViewFactory::class, fn () => new ViewFactory(
    $config->get('view.path', $projectRoot . '/resources/views')
));
```

Configuration recommandee :

```php
[
    'view.path' => $projectRoot . '/resources/views',
]
```

### WebRenderer

```php
use Velt\Ui\Renderers\WebRenderer;

$container->set(WebRenderer::class, fn () => new WebRenderer(
    fn (array $form): string => $container->get('csrf')->field()
));
```

Si le module CSRF n'est pas encore disponible :

```php
$container->set(WebRenderer::class, fn () => new WebRenderer());
```

Dans ce cas, `Form::csrf()` ne rend aucun champ `_token`. C'est volontaire.

### JsonRenderer

```php
use Velt\Ui\Renderers\JsonRenderer;

$container->set(JsonRenderer::class, fn () => new JsonRenderer());
```

## Flux HTTP recommande

Exemple de flux pour une route qui retourne une vue Velt.

```php
use Velt\Ui\Renderers\JsonRenderer;
use Velt\Ui\Renderers\WebRenderer;
use Velt\Ui\View\ViewFactory;

$viewName = 'auth.login';

$page = $container
    ->get(ViewFactory::class)
    ->make($viewName);

$renderer = $request->expectsPreviewJson()
    ? $container->get(JsonRenderer::class)
    : $container->get(WebRenderer::class);

$content = $renderer->render($page);

return new Response(
    body: $content,
    status: 200,
    headers: [
        'Content-Type' => $request->expectsPreviewJson()
            ? 'application/json; charset=utf-8'
            : 'text/html; charset=utf-8',
    ]
);
```

La methode `expectsPreviewJson()` est un exemple. Le kernel peut choisir un autre nom.

## Resolution des vues

`ViewFactory` utilise une notation par points.

```php
$page = $views->make('auth.login');
```

Ce nom pointe vers :

```text
resources/views/auth/login.velt.php
```

Une vue doit retourner une instance de `Velt\Ui\Page`.

```php
<?php

use Velt\Ui\Components\Text;
use Velt\Ui\Page;

return Page::make('Connexion')
    ->layout('auth')
    ->meta(['title' => 'Connexion - Velt App'])
    ->add(Text::make('Bienvenue')->as('h1'));
```

## Gestion des erreurs

Le kernel doit transformer les exceptions UI en reponses HTTP adaptees.

| Exception | Cause | Reponse kernel recommandee |
| --- | --- | --- |
| `ViewNotFoundException` | vue absente | 404 en production, page debug en dev |
| `InvalidArgumentException` | nom de vue dangereux | 400 ou erreur debug |
| `RuntimeException` | vue invalide, ne retourne pas `Page` | 500 ou erreur debug |

Exemple :

```php
try {
    $page = $views->make('auth.login');
} catch (ViewNotFoundException $e) {
    return $kernel->notFound($e);
}
```

## CSRF

`velt/ui` ne possede pas la session. Le kernel ou le module HTTP doit fournir le vrai champ CSRF.

Contrat attendu cote kernel :

```php
interface CsrfFieldProvider
{
    public function field(): string;
}
```

Exemple d'integration :

```php
$renderer = new WebRenderer(
    fn (array $form): string => $csrf->field()
);
```

Le resolver recoit le formulaire serialise en tableau. Cela permet plus tard d'adapter le token selon l'action, la methode ou un contexte specifique.

```php
fn (array $form): string => $csrf->field(
    action: $form['props']['action'] ?? null
)
```

## Preview JSON

Le kernel doit exposer un chemin ou un mode permettant de retourner le JSON Preview.

Exemples possibles :

```text
GET /_preview/auth.login
GET /auth/login?preview=json
Accept: application/vnd.velt.preview+json
```

Le choix final appartient au kernel. Le point important est que la sortie vienne de `JsonRenderer`.

```php
$json = (new JsonRenderer())->render($page);
```

Content-Type recommande :

```text
application/json; charset=utf-8
```

Schema actuel :

```json
{
    "schemaVersion": 1,
    "screen": "Connexion",
    "layout": "auth",
    "meta": {},
    "components": []
}
```

## Layout

Dans le Module 1, `layout('auth')` est seulement une intention.

Le kernel peut l'utiliser de deux facons :

1. laisser `WebRenderer` produire un document complet ;
2. rendre un fragment UI et l'injecter dans un layout kernel.

Document complet :

```php
$html = $webRenderer->render($page);
```

Fragment :

```php
$fragment = $webRenderer->render($page, ['document' => false]);
$html = $layoutRenderer->render($page->getLayout(), [
    'content' => $fragment,
    'meta' => $page->getMeta(),
]);
```

La strategie finale dependra du module kernel/view complet.

## Cache

Le kernel peut plus tard cacher :

- la page chargee ;
- l'arbre `toArray()` ;
- le HTML rendu ;
- le JSON Preview rendu.

Invalidation recommandee :

- en debug : pas de cache persistant ;
- en production : invalidation par modification du fichier `.velt.php` ou version d'application.

Le Module 1 ne doit pas ajouter de cache complexe dans `velt/ui`.

## API minimale attendue par le kernel

Le kernel peut fonctionner avec seulement ceci :

```php
use Velt\Ui\Renderers\JsonRenderer;
use Velt\Ui\Renderers\WebRenderer;
use Velt\Ui\View\ViewFactory;

$views = new ViewFactory($projectRoot . '/resources/views');

$page = $views->make('auth.login');

$html = (new WebRenderer($csrfResolver))->render($page);
$json = (new JsonRenderer())->render($page);
```

Cette API doit rester stable autant que possible.

## Checklist pour le dev kernel

- Definir le chemin `resources/views`.
- Enregistrer `ViewFactory`.
- Charger les fichiers `.velt.php`.
- Mapper les exceptions UI vers des reponses HTTP.
- Choisir `WebRenderer` ou `JsonRenderer`.
- Fournir un resolver CSRF au `WebRenderer` quand la session existe.
- Definir le content-type.
- Decider comment utiliser `layout`.
- Ne pas evaluer `showIf` cote kernel dans le Module 1 sans specification dediee.

## Ce qu'il ne faut pas faire

- Copier les classes UI dans le kernel.
- Faire dependre `velt/ui` du kernel.
- Faire dependre `velt/ui` d'une request HTTP concrete.
- Generer un faux token CSRF dans UI.
- Ajouter du HTML dans le JSON Preview.
- Transformer `.velt.php` en controleur.
