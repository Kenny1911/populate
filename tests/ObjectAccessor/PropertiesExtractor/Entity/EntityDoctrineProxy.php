<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Entity;

use Doctrine\Persistence\Proxy;

class EntityDoctrineProxy extends Entity implements Proxy
{
    public $proxyProperty;

    public function __load()
    {
    }

    public function __isInitialized()
    {
    }
}