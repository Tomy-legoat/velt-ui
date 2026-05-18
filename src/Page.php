<?php

declare(strict_types=1);

namespace Velt\Ui;

use Velt\Ui\Contracts\ViewInterface;
use Velt\Ui\Renderers\JsonRenderer;

/**
 * Class Page
 *
 * Représente une page complète dans Velt.
 *
 * Une page est le point d'entrée principal d'une interface UI.
 * Elle contient :
 *
 * - un titre
 * - un layout
 * - des meta données (SEO, description...)
 * - une liste de composants enfants
 *
 * Exemple d'utilisation :
 *
 * Page::make('Connexion')
 *     ->layout('auth')
 *     ->meta([
 *         'title' => 'Connexion - Velt App'
 *     ])
 *     ->add(
 *         Card::make()
 *     );
 */
class Page implements ViewInterface
{
    /**
     * Titre principal de la page.
     */
    protected string $title;

    /**
     * Nom du layout utilisé.
     *
     * Exemple : auth, dashboard, guest.
     */
    protected ?string $layout = null;

    /**
     * Métadonnées SEO de la page.
     */
    protected array $meta = [];

    /**
     * Composants enfants de la page.
     *
     * Exemple :
     * - Card
     * - Text
     * - Form
     * - Button
     */
    protected array $children = [];

    /**
     * Constructeur privé.
     *
     * On force l'utilisation de Page::make()
     * pour garder une syntaxe propre et déclarative.
     */
    private function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Crée une nouvelle instance de page.
     *
     * Exemple :
     *
     * Page::make('Login')
     */
    public static function make(string $title): self
    {
        return new self($title);
    }

    /**
     * Définit le layout de la page.
     *
     * Exemple :
     *
     * ->layout('auth')
     */
    public function layout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Définit les métadonnées de la page.
     *
     * Exemple :
     *
     * ->meta([
     *     'title' => 'Connexion',
     *     'description' => 'Page de connexion'
     * ])
     */
    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Ajoute un composant enfant.
     *
     * Exemple :
     *
     * ->add(Card::make())
     */
    public function add(object $component): self
    {
        $this->children[] = $component;

        return $this;
    }

    /**
     * Retourne tous les composants enfants.
     */
    public function children(): array
    {
        return $this->children;
    }

    /**
     * Retourne le titre de la page.
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Retourne le layout actuel.
     */
    public function getLayout(): ?string
    {
        return $this->layout;
    }

    /**
     * Retourne les métadonnées.
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Convertit la page en tableau.
     *
     * Cette structure sera utilisée par :
     *
     * - JsonRenderer
     * - Preview API
     * - potentiellement un renderer mobile futur
     */
    public function toArray(): array
    {
        return [
            'type' => 'page',
            'title' => $this->title,
            'layout' => $this->layout,
            'meta' => $this->meta,
            'children' => array_map(
                function ($child) {
                    if (method_exists($child, 'toArray')) {
                        return $child->toArray();
                    }

                    return $child;
                },
                $this->children
            ),
        ];
    }

    /**
     * Convertit la page en JSON.
     *
     * Utilisé principalement pour :
     *
     * - preview mobile
     * - API JSON
     * - QR Preview
     * - potentiellement un renderer mobile futur
     */
    public function toJson(): string
    {
        return (new JsonRenderer())->render($this);
    }
}
