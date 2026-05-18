<?php

declare(strict_types=1);

namespace Velt\Ui\Components;

/**
 * Class Button
 *
 * Composant de bouton.
 *
 * Exemple :
 *
 * Button::make('Se connecter')
 *     ->type('submit')
 *     ->variant('primary')
 *     ->class('w-full')
 *
 * Devient en HTML : <button type="submit" class="w-full variant-primary">Se connecter</button>
 */
class Button extends Component
{
    protected string $type = 'button';

    /**
     * Crée une nouvelle instance de Button.
     *
     * @param string $label Libellé du bouton
     */
    public static function make(string $label): self
    {
        $instance = new self();
        $instance->content = $label;

        return $instance;
    }

    /**
     * Définit le type du bouton.
     *
     * Exemple : 'submit', 'reset', 'button'
     *
     * @param string $type Type du bouton
     */
    public function type(string $type): self
    {
        return $this->prop('type', $type);
    }

    /**
     * Définit la variante visuelle du bouton.
     *
     * Exemple : 'primary', 'secondary', 'danger', 'success'
     *
     * @param string $variant Variante
     */
    public function variant(string $variant): self
    {
        return $this->prop('variant', $variant);
    }

    /**
     * Marque le bouton comme désactivé.
     */
    public function disabled(): self
    {
        return $this->prop('disabled', true);
    }
}