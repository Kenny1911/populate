<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Reflection;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\Reflection\GetterReflectionProperty;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class GetterReflectionPropertyTest extends TestCase
{
    public function testIsGetter()
    {
        $this->assertTrue(GetterReflectionProperty::isGetter($this->createReflectionMethod('getFoo')));
        $this->assertTrue(GetterReflectionProperty::isGetter($this->createReflectionMethod('isFoo')));
        $this->assertTrue(GetterReflectionProperty::isGetter($this->createReflectionMethod('hasFoo')));

        $this->assertFalse(GetterReflectionProperty::isGetter($this->createReflectionMethod('method')));
        $this->assertFalse(GetterReflectionProperty::isGetter($this->createReflectionMethod('getFoo', false)));
        $this->assertFalse(GetterReflectionProperty::isGetter($this->createReflectionMethod('getFoo', true, true)));
        $this->assertFalse(
            GetterReflectionProperty::isGetter($this->createReflectionMethod('getFoo', true, false, true))
        );
        $this->assertFalse(
            GetterReflectionProperty::isGetter($this->createReflectionMethod('getFoo', true, false, false, 1))
        );
    }

    private function createReflectionMethod(
        string $name,
        bool $public = true,
        bool $static = false,
        bool $abstract = false,
        int $parametersCount = 0
    ): ReflectionMethod
    {
        /** @var ReflectionMethod|MockObject $ref */
        $ref = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getName', 'isPublic', 'isStatic', 'isAbstract', 'getNumberOfRequiredParameters'])
            ->getMock()
        ;
        $ref->expects($this->any())->method('getName')->willReturn($name);
        $ref->expects($this->any())->method('isPublic')->willReturn($public);
        $ref->expects($this->any())->method('isStatic')->willReturn($static);
        $ref->expects($this->any())->method('isAbstract')->willReturn($abstract);
        $ref->expects($this->any())->method('getNumberOfRequiredParameters')->willReturn($parametersCount);

        return $ref;
    }
}
