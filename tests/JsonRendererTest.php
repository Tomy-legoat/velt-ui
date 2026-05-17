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
use Velt\Ui\Renderers\JsonRenderer;

class JsonRendererTest extends TestCase
{
    public function test_login_page_renders_preview_schema(): void
    {
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
                        ->showIf('guest')
                        ->add(Input::make('email', 'Email')->type('email')->required())
                        ->add(Input::make('password', 'Mot de passe')->type('password')->required())
                        ->add(Button::make('Se connecter')->type('submit')->variant('primary'))
                )
            );

        $json = (new JsonRenderer())->render($page);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(1, $data['schemaVersion']);
        $this->assertSame('Connexion', $data['screen']);
        $this->assertSame('auth', $data['layout']);
        $this->assertSame('Connexion - Velt App', $data['meta']['title']);
        $this->assertSame('Card', $data['components'][0]['type']);
        $this->assertSame('p-8', $data['components'][0]['props']['class']);

        $form = $data['components'][0]['children'][1];
        $this->assertSame('Form', $form['type']);
        $this->assertSame('POST', $form['props']['method']);
        $this->assertSame('/login', $form['props']['action']);
        $this->assertTrue($form['props']['csrf']);
        $this->assertSame('guest', $form['props']['showIf']);

        $input = $form['children'][0];
        $this->assertSame('Input', $input['type']);
        $this->assertSame('email', $input['name']);
        $this->assertSame('Email', $input['label']);
        $this->assertSame('email', $input['props']['type']);
        $this->assertTrue($input['props']['required']);

        $button = $form['children'][2];
        $this->assertSame('Button', $button['type']);
        $this->assertSame('submit', $button['props']['type']);
        $this->assertSame('primary', $button['props']['variant']);
        $this->assertSame('Se connecter', $button['content']);
    }

    public function test_all_mvp_components_are_represented_without_html(): void
    {
        $page = Page::make('Preview')
            ->add(Card::make())
            ->add(Text::make('Texte'))
            ->add(Alert::make('Alerte'))
            ->add(Form::make())
            ->add(Input::make('email', 'Email'))
            ->add(Button::make('OK'))
            ->add(Link::make('Accueil', '/'));

        $data = (new JsonRenderer())->toPreviewArray($page);

        $this->assertSame(
            ['Card', 'Text', 'Alert', 'Form', 'Input', 'Button', 'Link'],
            array_column($data['components'], 'type')
        );

        $json = json_encode($data, JSON_THROW_ON_ERROR);
        $this->assertStringNotContainsString('<', $json);
        $this->assertStringNotContainsString('>', $json);
    }
}
