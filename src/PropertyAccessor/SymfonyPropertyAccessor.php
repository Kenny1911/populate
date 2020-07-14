<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface as BasePropertyAccessorInterface;

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
            return $this->accessor->getValue($src, $name);
        }

        public function isReadable($src, string $name): bool
        {
            return $this->accessor->isReadable($src, $name);
        }

        public function setValue($src, string $name, $value): void
        {
            $this->accessor->setValue($src, $name, $value);
        }

        public function isWritable($src, string $name): bool
        {
            return $this->accessor->isWritable($src, $name);
        }
    }
}