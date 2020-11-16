<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

class Dest
{
    public $foo;
    protected $bar;
    private $baz;

    public function getBar()
    {
        return $this->bar;
    }

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function getBaz()
    {
        return $this->baz;
    }

    public function setBaz($value)
    {
        $this->baz = $value;
    }
}