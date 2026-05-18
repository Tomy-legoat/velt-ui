<?php

declare(strict_types=1);

namespace Velt\Ui\Components;

/**
 * Class Text
 *
 * Composant de texte pour afficher du contenu textuel.
 *
 * Exemple :
 *
 * Text::make('Bonjour le monde')
 *     ->as('h1')
 *     ->class('text-xl font-bold')
 *
 * Devient en HTML : <h1 class="text-xl font-bold">Bonjour le monde</h1>
 */
class Text extends Component
{
    protected string $type = 'text';

    /**
     * Crée une nouvelle instance de Text.
     *
     * @param string $content Contenu textuel
     */
    public static function make(string $content): self
    {
        $instance = new self();
        $instance->content = $content;

        return $instance;
    }

    /**
     * Définit le tag HTML utilisé pour le texte.
     *
     * Exemple : 'h1', 'h2', 'p', 'span'
     *
     * @param string $tag Tag HTML
     */
    public function as(string $tag): self
    {
        return $this->prop('as', $tag);
    }
}
