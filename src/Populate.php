<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessorInterface;

class Populate implements PopulateInterface
{
    /** @var ObjectAccessorInterface */
    protected $accessor;

    public function __construct(ObjectAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    public function populate($src, $dest, ?array $properties = null, array $mapping = []): void
    {
        $this->accessor->setData($dest, $this->accessor->getData($src, $properties, $mapping));
    }
}