<?php

declare(strict_types=1);

namespace Velt\Ui\View;

use RuntimeException;

final class ViewNotFoundException extends RuntimeException
{
    public static function forName(string $name, string $path): self
    {
        return new self(sprintf('View [%s] not found at [%s].', $name, $path));
    }
}
