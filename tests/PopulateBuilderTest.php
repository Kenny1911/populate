<?php

declare(strict_types=1);

use Kenny1911\Populate\AdvancedPopulate;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\PopulateBuilder;
use Kenny1911\Populate\Settings\SettingsInterface;
use Kenny1911\Populate\Tests\Dest;
use Kenny1911\Populate\Tests\Src;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;

class PopulateBuilderTest extends TestCase
{
    public function testSetPropertyAccessor()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setPropertyAccessor($accessor)->build();

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertSame($accessor, $this->getAccessor($populate));
    }

    public function testSetPropertyListExtractor()
    {
        $extractor = new ReflectionExtractor();

        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setPropertyListExtractor($extractor)->build();

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertSame($extractor, $this->getExtractor($populate));
    }

    public function testSetObjectAccessor()
    {
        $objectAccessor = new ObjectAccessor(
            PropertyAccess::createPropertyAccessor(),
            new ReflectionExtractor()
        );

        /** @var Populate $populate */
        $populate = PopulateBuilder::create()->setObjectAccessor($objectAccessor)->build();

        $this->assertInstanceOf(Populate::class, $populate);
        $this->assertSame($objectAccessor, $this->getObjectAccessor($populate));
    }

    public function testSetSettings()
    {
        $properties = ['public', 'private'];
        $ignoreProperties = ['private'];
        $mapping = ['public' => 'foo', 'protected' => 'bar', 'private' => 'baz'];

        /** @var AdvancedPopulate $populate */
        $populate = PopulateBuilder::create()
            ->setSettings([
                [
                    'src' => Src::class,
                    'dest' => Dest::class,
                    'properties' => $properties,
                    'ignore_properties' => $ignoreProperties,
                    'mapping' => $mapping
                ]
            ])
            ->build()
        ;

        $this->assertInstanceOf(AdvancedPopulate::class, $populate);
        $this->assertSame($properties, $this->getSettings($populate)->getProperties(Src::class, Dest::class));
        $this->assertSame(
            $ignoreProperties,
            $this->getSettings($populate)->getIgnoreProperties(Src::class, Dest::class)
        );
        $this->assertSame($mapping, $this->getSettings($populate)->getMapping(Src::class, Dest::class));
    }

    private function getObjectAccessor(Populate $populate): ObjectAccessor
    {
        return $this->getProperty($populate, 'accessor');
    }

    private function getAccessor(Populate $populate): PropertyAccessorInterface
    {
        return $this->getProperty($this->getObjectAccessor($populate), 'accessor');
    }

    private function getExtractor(Populate $populate): PropertyListExtractorInterface
    {
        return $this->getProperty($this->getObjectAccessor($populate), 'propertiesExtractor');
    }

    private function getSettings(AdvancedPopulate $populate): SettingsInterface
    {
        return $this->getProperty($populate, 'settings');
    }

    /**
     * @param object $object
     */
    private function getProperty($object, string $property)
    {
        $ref = new ReflectionProperty($object, $property);
        $ref->setAccessible(true);

        return $ref->getValue($object);
    }
}
