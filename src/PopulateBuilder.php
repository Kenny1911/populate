<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessorInterface;
use Kenny1911\Populate\Settings\Settings;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;

class PopulateBuilder
{
    /** @var PropertyAccessorInterface|null */
    private $accessor = null;

    /** @var PropertyListExtractorInterface|null */
    private $extractor = null;

    /** @var ObjectAccessorInterface|null */
    private $objectAccessor = null;

    /** @var array */
    private $settings = [];

    public static function create(): self
    {
        return new static();
    }

    public function setPropertyAccessor(?PropertyAccessorInterface $accessor): self
    {
        $this->accessor = $accessor;

        return $this;
    }

    public function setPropertyListExtractor(?PropertyListExtractorInterface $extractor): self
    {
        $this->extractor = $extractor;

        return $this;
    }

    public function setObjectAccessor(?ObjectAccessorInterface $objectAccessor): self
    {
        $this->objectAccessor = $objectAccessor;

        return $this;
    }

    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function build(): PopulateInterface
    {
        if ($this->objectAccessor) {
            $objectAccessor = $this->objectAccessor;
        } else {
            $accessor = $this->accessor ?? PropertyAccess::createPropertyAccessor();
            $extractor = $this->extractor ?? new ReflectionExtractor();

            $objectAccessor = new ObjectAccessor($accessor, $extractor);
        }

        $populate = new Populate($objectAccessor);

        if ($this->settings) {
            $populate = new AdvancedPopulate($populate, new Settings($this->settings));
        }

        return $populate;
    }
}