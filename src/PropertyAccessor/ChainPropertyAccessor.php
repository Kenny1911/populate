<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

use Kenny1911\Populate\Exception\InvalidArgumentException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;

class ChainPropertyAccessor implements PropertyAccessorInterface
{
    /** @var PropertyAccessorInterface[] */
    private $accessors = [];

    /** @var bool */
    private $disableExceptions;

    /**
     * @param PropertyAccessorInterface[] $accessors
     * @param bool $disableExceptions
     */
    public function __construct(iterable $accessors, bool $disableExceptions = false)
    {
        if (0 === count($accessors)) {
            throw new InvalidArgumentException('Empty array with property accessors.');
        }

        foreach ($accessors as $accessor) {
            if (!$accessor instanceof PropertyAccessorInterface) {
                throw new InvalidArgumentException(
                    sprintf('Property accessor must implements "%s".', PropertyAccessorInterface::class)
                );
            }

            $this->accessors[] = $accessor;
        }

        $this->disableExceptions = $disableExceptions;
    }

    public function getValue($src, string $name)
    {
        foreach ($this->accessors as $accessor) {
            try {
                return $accessor->getValue($src, $name);
            } catch (PropertyNotReadableException $e) {}
        }

        if ($this->disableExceptions) {
            return null;
        } else {
            throw new PropertyNotReadableException(get_class($src), $name);
        }
    }

    public function isReadable($src, string $name): bool
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->isReadable($src, $name)) {
                return true;
            }
        }

        return false;
    }

    public function setValue($src, string $name, $value): void
    {
        $isSet = false;
        foreach ($this->accessors as $accessor) {
            try {
                $accessor->setValue($src, $name, $value);
                $isSet = true;
            } catch (PropertyNotWritableException $e) {}
        }

        if (!$isSet && !$this->disableExceptions) {
            throw new PropertyNotWritableException(get_class($src), $name);
        }
    }

    public function isWritable($src, string $name): bool
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->isWritable($src, $name)) {
                return true;
            }
        }

        return false;
    }
}