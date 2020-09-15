<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Freezable;

interface FreezableInterface
{
    public function freeze(): void;

    public function isFrozen(): bool;
}