<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Entity;

class Entity
{
    public $foo;

    protected $bar;

    private $baz;

    public static $qux;

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param mixed $bar
     */
    public function setBar($bar): void
    {
        $this->bar = $bar;
    }

    /**
     * @return mixed
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param mixed $baz
     */
    public function setBaz($baz): void
    {
        $this->baz = $baz;
    }
}