<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Freezable;

use Kenny1911\Populate\Exception\FrozenException;

trait FreezableTrait
{
    /** @var bool */
    protected $frozen = false;

    public function freeze(): void
    {
        $this->frozen = true;
    }

    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    protected function throwFrozenException(string $message = '', int $code = 0): void
    {
        if ($this->isFrozen()) {
            throw new FrozenException($message, $code);
        }
    }
}