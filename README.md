# Sous-module 04 - UI Rendering

## Mission

Ce sous-module est le coeur distinctif de Velt. Il fournit la syntaxe declarative officielle en PHP, construit un arbre UI en memoire, puis le rend soit en HTML pour le Web, soit en JSON pour la preview mobile.

Apres audit, UI doit expliciter ses contrats internes. `Page::make()` est une tres bonne base, mais le moteur doit definir `ComponentInterface`, `RendererInterface` et une strategie de serialisation stable pour eviter que WebRenderer, JsonRenderer et Preview deviennent couples a des details internes.

## Perimetre

Inclus :

- `Page`
- composants `Text`, `Button`, `Card`, `Form`, `Input`, `Link`, `Alert`
- `ComponentInterface`
- `RendererInterface`
- `ViewInterface` ou contrat equivalent pour les pages chargees ;
- props chainables ;
- rendu HTML MVP ;
- rendu JSON MVP ;
- schema JSON versionne ;
- escaping HTML obligatoire ;
- tests snapshot simples.

Exclus :

- compilation type React/Svelte ;
- Tailwind runtime ;
- etat interactif avance ;
- hot reload.

## Comment tester sans HTTP ou Preview

UI doit etre testable comme une bibliotheque pure.

- Une page est instanciee directement en PHP et comparee avec `toArray()`.
- Le HTML est teste par snapshots ou assertions DOM simples, sans lancer de serveur HTTP.
- Le JSON preview est teste avec `json_decode()` et assertions sur `screen`, `schemaVersion` et `components`.
- `ViewFactory` charge des fichiers `.velt.php` depuis un dossier temporaire.
- CSRF ne doit pas generer un vrai token dans UI. UI marque seulement l'intention `csrf: true`; HTTP/session transforme cette intention en champ reel.
- Les composants echappent les textes et attributs dangereux dans le renderer HTML.

L'integration avec `veltphp/http` est testee plus tard par le sous-module 07. UI ne doit pas dependre de `Request` ou `Response`.

## Issues

- [Issue 01 - Creer Page et composants UI](issues/01-creer-page-composants-ui.md)
- [Issue 02 - Implementer WebRenderer HTML](issues/02-implementer-web-renderer-html.md)
- [Issue 03 - Implementer JsonRenderer preview](issues/03-implementer-json-renderer-preview.md)
- [Issue 04 - Ajouter ViewFactory et chargement des pages Velt](issues/04-ajouter-viewfactory-chargement-pages-velt.md)
- [Issue 05 - Definir contrats Component Renderer View](issues/05-definir-contrats-component-renderer-view.md)
- [Issue 06 - Cadrer cache compilation et schema UI](issues/06-cadrer-cache-compilation-schema-ui.md)

## Exemple officiel (Issue 01)

```php
use Veltphp\Ui\Page;
use Veltphp\Ui\Components\Card;
use Veltphp\Ui\Components\Text;
use Veltphp\Ui\Components\Button;
use Veltphp\Ui\Components\Form;
use Veltphp\Ui\Components\Input;

$page = Page::make('Connexion')
    ->layout('auth')
    ->meta(['title' => 'Connexion - Velt App'])
    ->add(
        Card::make()->class('p-8')->add(
            Text::make('Connexion')->as('h1')
        )->add(
            Form::make()
                ->method('POST')
                ->action('/login')
                ->csrf()
                ->add(
                    Input::make('email', 'Email')
                        ->type('email')
                        ->required()
                        ->placeholder('Entrez votre email')
                )->add(
                    Input::make('password', 'Mot de passe')
                        ->type('password')
                        ->required()
                        ->placeholder('Entrez votre mot de passe')
                )->add(
                    Button::make('Se connecter')
                        ->type('submit')
                        ->variant('primary')
                        ->class('w-full')
                )
        )
    );

// Sérialiser en tableau
$data = $page->toArray();

// Sérialiser en JSON
$json = $page->toJson();
```
