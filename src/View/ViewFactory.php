<?php

declare(strict_types=1);

namespace Velt\Ui\View;

use InvalidArgumentException;
use RuntimeException;
use Velt\Ui\Page;

/**
 * Charge les fichiers declaratifs .velt.php depuis une racine configuree.
 *
 * La factory resout uniquement des fichiers de vues et verifie qu'ils
 * retournent Page. Elle n'execute pas de controleur et n'injecte pas d'etat HTTP.
 */
final class ViewFactory
{
    public function __construct(
        private readonly string $root
    ) {
    }

    public function make(string $name): Page
    {
        $path = $this->pathFor($name);

        if (! is_file($path)) {
            throw ViewNotFoundException::forName($name, $path);
        }

        $page = require $path;

        if (! $page instanceof Page) {
            throw new RuntimeException(sprintf(
                'View [%s] must return an instance of %s.',
                $name,
                Page::class
            ));
        }

        return $page;
    }

    public function pathFor(string $name): string
    {
        // La notation par points represente les dossiers. Les separateurs de
        // chemin et marqueurs de traversee sont refuses avant de construire le chemin.
        if (! preg_match('/^[A-Za-z0-9_-]+(?:\.[A-Za-z0-9_-]+)*$/', $name)) {
            throw new InvalidArgumentException(sprintf('View name [%s] is not safe.', $name));
        }

        $relativePath = str_replace('.', DIRECTORY_SEPARATOR, $name) . '.velt.php';

        return rtrim($this->root, DIRECTORY_SEPARATOR . '/') . DIRECTORY_SEPARATOR . $relativePath;
    }
}
