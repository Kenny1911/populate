<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

class Src
{
    public $public;
    protected $protected;
    private $private;

    public function __construct($public = null, $protected = null, $private = null)
    {
        $this->public = $public;
        $this->protected = $protected;
        $this->private = $private;
    }

    public function getProtected()
    {
        return $this->protected;
    }

    public function setProtected(int $value)
    {
        $this->protected = $value;
    }

    public function getPrivate()
    {
        return $this->private;
    }

    public function setPrivate(int $value)
    {
        $this->private = $value;
    }
}