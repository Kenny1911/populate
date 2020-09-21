<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Exception\PropertyAccessor;

use Kenny1911\Populate\Exception\RuntimeException;
use Throwable;

class PropertyNotWritableException extends RuntimeException
{
    const MESSAGE = 'Property %s::$%s is not writable.';

    public function __construct(string $class, string $property, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(static::MESSAGE, $class, $property), $code, $previous);
    }
}