<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\Reflection;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionMethod;
use ReflectionProperty;

class GetterReflectionProperty extends ReflectionProperty
{
    const GETTER_PATTERN = '/^(get|has|is)(\w+)$/';

    /** @var ReflectionMethod */
    private $getter;

    /** @var string */
    private $name;

    /** @noinspection PhpMissingParentConstructorInspection */
    /**
     * GetterReflectionProperty constructor.
     * @param ReflectionMethod $getter
     */
    public function __construct(ReflectionMethod $getter)
    {
        if (!static::isGetter($getter)) {
            throw new RuntimeException(sprintf('Reflection method "%s" is not getter.', $getter->getName()));
        }

        $this->getter = $getter;

        preg_match('/^(get|has|is)(\w+)$/', $getter->getName(), $matches);
        $this->name = lcfirst($matches[2]);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue($object = null)
    {
        return $this->getter->invoke($object);
    }

    /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
    public function setValue($objectOrValue, $value)
    {
        throw new RuntimeException('Cannot set value of property.');
    }

    public function isPublic()
    {
        return true;
    }

    public function isPrivate()
    {
        return false;
    }

    public function isProtected()
    {
        return false;
    }

    public function isStatic()
    {
        return false;
    }

    public function isDefault()
    {
        return false;
    }

    public function getModifiers()
    {
        return $this->getter->getModifiers();
    }

    public function getDeclaringClass()
    {
        return $this->getter->getDeclaringClass();
    }

    public function getDocComment()
    {
        return $this->getter->getDocComment();
    }

    public function setAccessible($accessible)
    {
        $this->getter->setAccessible(true);
    }

    public function getType()
    {
        return $this->getter->getReturnType();
    }

    public function hasType()
    {
        return $this->getter->hasReturnType();
    }

    /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
    public function isInitialized($object)
    {
        return true;
    }

    public static function isGetter(ReflectionMethod $method): bool
    {
        return (
            $method->isPublic() &&
            !$method->isStatic() &&
            !$method->isAbstract() &&
            (bool)preg_match(static::GETTER_PATTERN, $method->getName()) &&
            0 === $method->getNumberOfRequiredParameters()
        );
    }
}