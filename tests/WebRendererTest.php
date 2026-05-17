<?php

declare(strict_types=1);

namespace Velt\Ui\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Ui\Components\Alert;
use Velt\Ui\Components\Button;
use Velt\Ui\Components\Card;
use Velt\Ui\Components\Form;
use Velt\Ui\Components\Input;
use Velt\Ui\Components\Link;
use Velt\Ui\Components\Text;
use Velt\Ui\Page;
use Velt\Ui\Renderers\WebRenderer;

class WebRendererTest extends TestCase
{
    public function test_login_page_renders_stable_html_document(): void
    {
        $page = Page::make('Connexion')
            ->layout('auth')
            ->meta([
                'title' => 'Connexion - Velt App',
                'description' => 'Page de connexion',
            ])
            ->add(
                Card::make()->class('p-8')->add(
                    Text::make('Connexion')->as('h1')
                )->add(
                    Alert::make('Identifiants invalides')->type('error')
                )->add(
                    Form::make()
                        ->method('POST')
                        ->action('/login')
                        ->csrf()
                        ->add(Input::make('email', 'Email')->type('email')->required()->placeholder('Entrez votre email'))
                        ->add(Input::make('password', 'Mot de passe')->type('password')->required())
                        ->add(Button::make('Se connecter')->type('submit')->variant('primary')->class('w-full'))
                )->add(
                    Link::make('Mot de passe oublie', '/password/reset')
                )
            );

        $html = (new WebRenderer())->render($page);

        $expected = <<<'HTML'
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Velt App</title>
    <meta name="description" content="Page de connexion">
</head>
<body>
<section class="p-8">
<h1>Connexion</h1>
<div role="alert" data-alert-type="error">Identifiants invalides</div>
<form method="POST" action="/login">
<label for="email">Email</label><input id="email" type="email" name="email" placeholder="Entrez votre email" required>
<label for="password">Mot de passe</label><input id="password" type="password" name="password" required>
<button type="submit" class="w-full" data-variant="primary">Se connecter</button>
</form>
<a href="/password/reset">Mot de passe oublie</a>
</section>
</body>
</html>
HTML;

        $this->assertSame($expected, $html);
    }

    public function test_fragment_option_renders_only_children(): void
    {
        $page = Page::make('Fragment')->add(Text::make('Bonjour')->as('h2'));

        $html = (new WebRenderer())->render($page, ['document' => false]);

        $this->assertSame('<h2>Bonjour</h2>', $html);
    }

    public function test_escapes_text_attributes_and_invalid_text_tags(): void
    {
        $page = Page::make('Danger')
            ->add(
                Text::make('<script>alert("x")</script>')
                    ->as('script')
                    ->class('" onclick="alert(1)')
            )
            ->add(
                Link::make('Lien <dangereux>', '/search?q="<x>"')
            );

        $html = (new WebRenderer())->render($page, ['document' => false]);

        $this->assertSame(
            '<p class="&quot; onclick=&quot;alert(1)">&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;</p>' . "\n"
            . '<a href="/search?q=&quot;&lt;x&gt;&quot;">Lien &lt;dangereux&gt;</a>',
            $html
        );
    }

    public function test_csrf_without_session_does_not_generate_fake_token(): void
    {
        $page = Page::make('CSRF')
            ->add(Form::make()->method('POST')->action('/submit')->csrf());

        $html = (new WebRenderer())->render($page, ['document' => false]);

        $this->assertSame('<form method="POST" action="/submit"></form>', $html);
        $this->assertStringNotContainsString('_token', $html);
    }

    public function test_csrf_can_be_delegated_to_http_session_contract(): void
    {
        $renderer = new WebRenderer(
            fn (array $form): string => '<input type="hidden" name="_token" value="real-token">'
        );

        $page = Page::make('CSRF')
            ->add(Form::make()->method('POST')->action('/submit')->csrf());

        $html = $renderer->render($page, ['document' => false]);

        $this->assertSame(
            '<form method="POST" action="/submit"><input type="hidden" name="_token" value="real-token"></form>',
            $html
        );
    }
}
