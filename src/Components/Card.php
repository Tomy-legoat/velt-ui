<?php

declare(strict_types=1);

namespace Velt\Ui\Components;

/**
 * Class Card
 *
 * Composant de carte pour grouper du contenu.
 *
 * Exemple :
 *
 * Card::make()
 *     ->class('p-8 rounded-lg shadow')
 *     ->add(Text::make('Titre')->as('h2'))
 *     ->add(Text::make('Description'))
 *     ->add(Button::make('Lire plus'))
 */
class Card extends Component
{
    protected string $type = 'card';

    /**
     * Crée une nouvelle instance de Card.
     */
    public static function make(): self
    {
        return new self();
    }
}
