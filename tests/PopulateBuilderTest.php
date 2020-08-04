<?php

declare(strict_types=1);

use Kenny1911\Populate\AdvancedPopulate;
use Kenny1911\Populate\Exception\LogicException;
use Kenny1911\Populate\FreezablePopulateSettingsStorage;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\PopulateBuilder;
use Kenny1911\Populate\PopulateInterface;
use Kenny1911\Populate\PopulateSettingsStorage;
use Kenny1911\Populate\PopulateSettingsStorageInterface;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use Kenny1911\Populate\PropertyAccessor\SymfonyPropertyAccessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PopulateBuilderTest extends TestCase
{
    public function testBuildSimple()
    {
        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->build();
        $objectAccessor = $this->getObjectAccessor($populate);
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(ReflectionPropertyAccessor::class, $propertyAccessor);
    }

    public function testBuildWithCustomObjectAccessor()
    {
        $objectAccessor = new ObjectAccessor(new ReflectionPropertyAccessor());
        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setObjectAccessor($objectAccessor)->build();

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertSame($objectAccessor, $this->getObjectAccessor($populate));
    }

    public function testBuildWithReflectionAccessorType()
    {
        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setReflectionPropertyAccessor()->build();
        $objectAccessor = $this->getObjectAccessor($populate);
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(ReflectionPropertyAccessor::class, $propertyAccessor);
    }

    public function testBuildWithSymfonyAccessorType()
    {
        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setSymfonyPropertyAccessor()->build();
        $objectAccessor = $this->getObjectAccessor($populate);
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(SymfonyPropertyAccessor::class, $propertyAccessor);
    }

    public function testBuildWithSymfonyAccessorTypeWithCustomPropertyAccessor()
    {
        $symfonyPropertyAccessor = PropertyAccess::createPropertyAccessor();

        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setSymfonyPropertyAccessor($symfonyPropertyAccessor)->build();
        $objectAccessor = $this->getObjectAccessor($populate);
        /** @var SymfonyPropertyAccessor $propertyAccessor */
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(SymfonyPropertyAccessor::class, $propertyAccessor);
        $this->assertSame($symfonyPropertyAccessor, $this->getOriginalSymfonyPropertyAccessor($propertyAccessor));
    }

    public function testBuildWithCustomPropertyAccessorInterface()
    {
        $customPropertyAccessor = new ReflectionPropertyAccessor();

        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setPropertyAccessor($customPropertyAccessor)->build();
        $objectAccessor = $this->getObjectAccessor($populate);
        /** @var SymfonyPropertyAccessor $propertyAccessor */
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(ReflectionPropertyAccessor::class, $propertyAccessor);
        $this->assertSame($customPropertyAccessor, $propertyAccessor);
    }

    public function testBuildWithSettings()
    {
        /** @var AdvancedPopulate $advancedPopulate */
        $advancedPopulate = PopulateBuilder::create()
            ->setProperties('Foo', 'Bar', ['prop1', 'prop2', 'prop3'])
            ->setMapping('Foo', 'Bar', ['prop1' => 'foo', 'prop2' => 'bar', 'prop3' => 'baz'])
            ->build()
        ;
        /** @var Populate $populate */
        $populate = $this->getOriginalPopulate($advancedPopulate);
        $objectAccessor = $this->getObjectAccessor($populate);
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);
        $settings = $this->getSettings($advancedPopulate);

        $this->assertInstanceOf(AdvancedPopulate::class, $advancedPopulate);
        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(ReflectionPropertyAccessor::class, $propertyAccessor);
        $this->assertInstanceOf(PopulateSettingsStorage::class, $settings);
        $this->assertSame(['prop1', 'prop2', 'prop3'], $settings->getProperties('Foo', 'Bar'));
        $this->assertSame(['prop1' => 'foo', 'prop2' => 'bar', 'prop3' => 'baz'], $settings->getMapping('Foo', 'Bar'));
    }

    public function testBuildWithFrozenSettings()
    {
        /** @var AdvancedPopulate $advancedPopulate */
        $advancedPopulate = PopulateBuilder::create()
            ->setProperties('Foo', 'Bar', ['prop1', 'prop2', 'prop3'])
            ->setMapping('Foo', 'Bar', ['prop1' => 'foo', 'prop2' => 'bar', 'prop3' => 'baz'])
            ->freezeSettings()
            ->build()
        ;
        /** @var Populate $populate */
        $populate = $this->getOriginalPopulate($advancedPopulate);
        $objectAccessor = $this->getObjectAccessor($populate);
        $propertyAccessor = $this->getPropertyAccessor($objectAccessor);
        $freezableSettings = $this->getSettings($advancedPopulate);
        $settings = $this->getOriginalSettings($freezableSettings);

        $this->assertInstanceOf(AdvancedPopulate::class, $advancedPopulate);
        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertInstanceOf(ObjectAccessor::class, $objectAccessor);
        $this->assertInstanceOf(ReflectionPropertyAccessor::class, $propertyAccessor);
        $this->assertInstanceOf(FreezablePopulateSettingsStorage::class, $freezableSettings);
        $this->assertInstanceOf(PopulateSettingsStorage::class, $settings);
        $this->assertSame(['prop1', 'prop2', 'prop3'], $freezableSettings->getProperties('Foo', 'Bar'));
        $this->assertSame(['prop1' => 'foo', 'prop2' => 'bar', 'prop3' => 'baz'], $settings->getMapping('Foo', 'Bar'));
    }

    public function testSetObjectAccessorAfterPropertyAccessor()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Object Accessor cannot be set after define property Property Accessor.');

        PopulateBuilder::create()
            ->setPropertyAccessor(new ReflectionPropertyAccessor())
            ->setObjectAccessor(new ObjectAccessor(new ReflectionPropertyAccessor()))
            ->build()
        ;
    }

    public function testSetPropertyAccessorAfterObjectAccessor()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Property Accessor cannot be set after define Object Accessor.');

        PopulateBuilder::create()
            ->setObjectAccessor(new ObjectAccessor(new ReflectionPropertyAccessor()))
            ->setPropertyAccessor(new ReflectionPropertyAccessor())
            ->build()
        ;
    }

    public function testBuildInvalidAccessorType()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Invalid property accessor type.');

        $builder = PopulateBuilder::create();

        /** @noinspection PhpUnhandledExceptionInspection */
        $ref = new ReflectionProperty($builder, 'propertyAccessorType');
        $ref->setAccessible(true);
        $ref->setValue($builder, 'invalid');

        $builder->build();
    }

    private function getOriginalPopulate(PopulateInterface $populate): PopulateInterface
    {
        if ($populate instanceof AdvancedPopulate) {
            return $this->getProperty($populate, 'populate');
        }

        return $populate;
    }

    private function getObjectAccessor(Populate $populate): ObjectAccessor
    {
        return $this->getProperty($populate, 'accessor');
    }

    private function getPropertyAccessor(ObjectAccessor $accessor): PropertyAccessorInterface
    {
        return $this->getProperty($accessor, 'accessor');
    }

    private function getOriginalSymfonyPropertyAccessor(SymfonyPropertyAccessor $accessor): PropertyAccessor
    {
        return $this->getProperty($accessor, 'accessor');
    }

    private function getSettings(AdvancedPopulate $populate): PopulateSettingsStorageInterface
    {
        return $this->getProperty($populate, 'settings');
    }

    private function getOriginalSettings(PopulateSettingsStorageInterface $settings): PopulateSettingsStorageInterface
    {
        if ($settings instanceof FreezablePopulateSettingsStorage) {
            return $this->getProperty($settings, 'settings');
        }

        return $settings;
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getProperty($object, string $property)
    {
        try {
            $ref = new ReflectionProperty($object, $property);
            $ref->setAccessible(true);

            return $ref->getValue($object);
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
