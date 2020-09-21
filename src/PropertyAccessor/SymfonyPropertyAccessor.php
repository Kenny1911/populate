<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface as BasePropertyAccessorInterface;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

if (interface_exists(BasePropertyAccessorInterface::class)) {
    class SymfonyPropertyAccessor implements PropertyAccessorInterface
    {
        protected $accessor;

        public function __construct(BasePropertyAccessorInterface $accessor = null)
        {
            $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
        }

        public function getValue($src, string $name)
        {
            try {
                return $this->accessor->getValue($src, $name);
            } catch (ExceptionInterface $e) {
                throw new PropertyNotReadableException(get_class($src), $name, $e->getCode(), $e);
            }
        }

        public function isReadable($src, string $name): bool
        {
            return $this->accessor->isReadable($src, $name);
        }

        public function setValue($src, string $name, $value): void
        {
            try {
                $this->accessor->setValue($src, $name, $value);
            } catch (ExceptionInterface $e) {
                throw new PropertyNotWritableException(get_class($src), $name, $e->getCode(), $e);
            }
        }

        public function isWritable($src, string $name): bool
        {
            return $this->accessor->isWritable($src, $name);
        }
    }
}