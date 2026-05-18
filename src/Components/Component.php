<?php

declare(strict_types=1);

namespace Velt\Ui\Components;

use Velt\Ui\Contracts\ComponentInterface;

/**
 * Class Component
 *
 * Classe abstraite de base pour tous les composants Velt.
 *
 * Cette classe gère :
 * - Le type de composant
 * - Les props (attributs et propriétés)
 * - Les enfants (children)
 * - Le contenu textuel
 * - La sérialisation en tableau
 *
 * Exemple :
 *
 * $button = Button::make('Cliquer')
 *     ->type('submit')
 *     ->class('btn-primary');
 *
 * $card = Card::make()
 *     ->class('p-8')
 *     ->add(Text::make('Bonjour'));
 */
abstract class Component implements ComponentInterface
{
    /**
     * Type du composant.
     *
     * Exemple : 'text', 'button', 'card', 'form', 'input', 'link', 'alert'
     */
    protected string $type;

    /**
     * Propriétés du composant (props).
     *
     * Exemples :
     * - class: 'btn btn-primary'
     * - type: 'submit'
     * - variant: 'primary'
     * - href: '/dashboard'
     * - required: true
     */
    protected array $props = [];

    /**
     * Composants enfants.
     */
    protected array $children = [];

    /**
     * Contenu textuel du composant.
     */
    protected ?string $content = null;

    /**
     * Constructeur privé.
     *
     * Force l'utilisation de la factory method.
     */
    final protected function __construct()
    {
    }

    /**
     * Définit une prop sur le composant.
     *
     * Exemple :
     *
     * ->prop('class', 'btn-primary')
     *
     * @param string $key Clé de la prop
     * @param mixed $value Valeur de la prop
     */
    protected function prop(string $key, mixed $value): static
    {
        $this->props[$key] = $value;

        return $this;
    }

    /**
     * Définit la classe CSS du composant.
     *
     * Exemple :
     *
     * ->class('btn btn-primary')
     */
    public function class(string $class): static
    {
        return $this->prop('class', $class);
    }

    /**
     * Conserve une condition logique pour les renderers qui la comprennent.
     *
     * Le renderer preview la serialise sans l'evaluer dans le Module 1.
     */
    public function showIf(mixed $condition): static
    {
        return $this->prop('showIf', $condition);
    }

    /**
     * Ajoute un enfant au composant.
     *
     * Exemple :
     *
     * ->add(Text::make('Texte'))
     */
    public function add(object $child): static
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Définit les enfants du composant.
     *
     * Exemple :
     *
     * ->children([
     *     Text::make('Texte'),
     *     Button::make('Cliquer'),
     * ])
     */
    public function children(array $children): static
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Retourne les enfants du composant.
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Retourne les props du composant.
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * Retourne le type du composant.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Retourne le contenu textuel du composant.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Convertit le composant en tableau.
     *
     * Cette méthode est appelée par les renderers (WebRenderer, JsonRenderer).
     *
     * @return array Structure : ['type' => '...', 'props' => [...], 'content' => '...', 'children' => [...]]
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->type,
            'props' => $this->props,
        ];

        if ($this->content !== null) {
            $array['content'] = $this->content;
        }

        if (! empty($this->children)) {
            $array['children'] = array_map(
                function ($child) {
                    if (method_exists($child, 'toArray')) {
                        return $child->toArray();
                    }

                    return $child;
                },
                $this->children
            );
        }

        return $array;
    }
}
