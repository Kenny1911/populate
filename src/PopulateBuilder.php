<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\Exception\LogicException;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessorInterface;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use Kenny1911\Populate\PropertyAccessor\SymfonyPropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface as SymfonyPropertyAccessorInterface;

class PopulateBuilder
{
    const PROPERTY_ACCESSOR_TYPE_REFLECTION = 'reflection';

    const PROPERTY_ACCESSOR_TYPE_SYMFONY = 'symfony';

    /** @var ObjectAccessorInterface|null */
    protected $objectAccessor;

    /** @var PropertyAccessorInterface|null */
    protected $propertyAccessor;

    /** @var string */
    protected $propertyAccessorType = self::PROPERTY_ACCESSOR_TYPE_REFLECTION;

    /** @var SymfonyPropertyAccessorInterface|null */
    protected $symfonyPropertyAccessor;

    /** @var PopulateSettingsStorageInterface|null */
    protected $settings;

    /** @var bool */
    protected $freezeSettings = false;

    public static function create(): self
    {
        return new static();
    }

    public function build(): PopulateInterface
    {
        if ($this->objectAccessor) {
            $populate = new Populate($this->objectAccessor);
        } else {
            $populate = new Populate(new ObjectAccessor($this->initPropertyAccessor()));
        }

        if ($this->settings) {
            $settings = $this->settings;

            if ($this->freezeSettings) {
                $settings = new FreezablePopulateSettingsStorage($settings);
                $settings->freeze();
            }

            $populate = new AdvancedPopulate($populate, $settings);
        }

        return $populate;
    }

    public function setObjectAccessor(ObjectAccessorInterface $accessor): self
    {
        if ($this->propertyAccessor) {
            throw new LogicException('Object Accessor cannot be set after define property Property Accessor.');
        }

        $this->objectAccessor = $accessor;

        return $this;
    }

    public function setPropertyAccessor(PropertyAccessorInterface $accessor): self
    {
        if ($this->objectAccessor) {
            throw new LogicException('Property Accessor cannot be set after define Object Accessor.');
        }

        $this->propertyAccessor = $accessor;

        return $this;
    }

    public function setReflectionPropertyAccessor(): self
    {
        $this->propertyAccessorType = static::PROPERTY_ACCESSOR_TYPE_REFLECTION;

        return $this;
    }

    public function setSymfonyPropertyAccessor(SymfonyPropertyAccessorInterface $accessor = null): self
    {
        $this->propertyAccessorType = static::PROPERTY_ACCESSOR_TYPE_SYMFONY;
        $this->symfonyPropertyAccessor = $accessor;

        return $this;
    }

    /**
     * @param object|string $src
     * @param object|string $dest
     * @param array|null    $properties
     *
     * @return PopulateBuilder
     */
    public function setProperties($src, $dest, ?array $properties): self
    {
        $this->initSettingsStorage();

        $this->settings->setProperties($src, $dest, $properties);

        return $this;
    }

    /**
     * @param object|string $src
     * @param object|string $dest
     * @param string[]      $mapping
     *
     * @return PopulateBuilder
     */
    public function setMapping($src, $dest, array $mapping): self
    {
        $this->initSettingsStorage();

        $this->settings->setMapping($src, $dest, $mapping);

        return $this;
    }

    public function freezeSettings(): self
    {
        $this->freezeSettings = true;

        return $this;
    }

    protected function initSettingsStorage(): void
    {
        if (!$this->settings) {
            $this->settings = new PopulateSettingsStorage();
        }
    }

    protected function initPropertyAccessor(): PropertyAccessorInterface
    {
        if ($this->propertyAccessor) {
            return $this->propertyAccessor;
        }

        switch ($this->propertyAccessorType) {
            case static::PROPERTY_ACCESSOR_TYPE_REFLECTION:
                return new ReflectionPropertyAccessor();

            case static::PROPERTY_ACCESSOR_TYPE_SYMFONY:
                return new SymfonyPropertyAccessor($this->symfonyPropertyAccessor);
        }

        throw new LogicException('Invalid property accessor type.');
    }
}