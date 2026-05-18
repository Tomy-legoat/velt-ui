<?php

declare(strict_types=1);

namespace Velt\Ui\Contracts;

/**
 * Contrat d'une vue Velt chargeable.
 *
 * Dans le Module 1, l'implementation concrete est Page. Le contrat separe
 * evite au kernel et a Preview de dependre des details internes de Page.
 */
interface ViewInterface
{
    public function title(): string;

    public function getLayout(): ?string;

    public function getMeta(): array;

    public function children(): array;

    public function toArray(): array;
}
