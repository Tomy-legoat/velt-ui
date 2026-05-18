<?php

declare(strict_types=1);

namespace Velt\Ui\Components;

/**
 * Class Alert
 *
 * Composant d'alerte pour afficher des messages de notification.
 *
 * Exemple :
 *
 * Alert::make('Une erreur est survenue')
 *     ->type('error')
 *     ->class('text-red-700 bg-red-100')
 *
 * Devient en HTML : <div role="alert" class="text-red-700 bg-red-100">Une erreur est survenue</div>
 */
class Alert extends Component
{
    protected string $type = 'alert';

    /**
     * Crée une nouvelle instance d'Alert.
     *
     * @param string $message Message d'alerte
     */
    public static function make(string $message): self
    {
        $instance = new self();
        $instance->content = $message;

        return $instance;
    }

    /**
     * Définit le type d'alerte.
     *
     * Exemple : 'info', 'success', 'warning', 'error'
     *
     * @param string $alertType Type d'alerte
     */
    public function alertType(string $alertType): self
    {
        return $this->prop('alertType', $alertType);
    }

    /**
     * Alias pour alertType.
     *
     * @param string $alertType Type d'alerte
     */
    public function type(string $alertType): self
    {
        return $this->alertType($alertType);
    }
}
