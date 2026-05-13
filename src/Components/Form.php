<?php

declare(strict_types=1);

namespace Veltphp\Ui\Components;

/**
 * Class Form
 *
 * Composant de formulaire pour grouper des champs de saisie.
 *
 * Exemple :
 *
 * Form::make()
 *     ->method('POST')
 *     ->action('/login')
 *     ->csrf()
 *     ->add(Input::make('email', 'Email')->type('email')->required())
 *     ->add(Input::make('password', 'Mot de passe')->type('password')->required())
 *     ->add(Button::make('Se connecter')->type('submit'))
 */
class Form extends Component
{
    protected string $type = 'form';

    /**
     * Crée une nouvelle instance de Form.
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * Définit la méthode HTTP du formulaire.
     *
     * Exemple : 'GET', 'POST', 'PUT', 'DELETE'
     *
     * @param string $method Méthode HTTP
     */
    public function method(string $method): self
    {
        return $this->prop('method', strtoupper($method));
    }

    /**
     * Définit l'action du formulaire.
     *
     * @param string $action URL d'action
     */
    public function action(string $action): self
    {
        return $this->prop('action', $action);
    }

    /**
     * Marque le formulaire comme nécessitant un token CSRF.
     *
     * Note : Le token réel sera généré par le middleware HTTP.
     */
    public function csrf(): self
    {
        return $this->prop('csrf', true);
    }
}
