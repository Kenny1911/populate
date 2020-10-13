<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Doctrine\Persistence\Proxy;
use Kenny1911\Populate\Exception\LogicException;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\DoctrineProxyPropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Entity\Entity;
use Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Entity\EntityDoctrineProxy;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class DoctrineProxyPropertiesExtractorTest extends TestCase
{
    /** @var PropertiesExtractor */
    private $innerExtractor;

    /** @var DoctrineProxyPropertiesExtractor */
    private $extractor;

    /**
     * @throws ReflectionException
     */
    public function test()
    {
        $this->assertSame(
            ['proxyProperty', 'foo', 'bar'],
            $this->innerExtractor->getProperties(EntityDoctrineProxy::class)
        );
        $this->assertSame(['foo', 'bar', 'baz'], $this->extractor->getProperties(EntityDoctrineProxy::class));
    }

    /**
     * @throws ReflectionException
     */
    public function testNoParentClass()
    {
        $obj = new class implements Proxy {

            public function __load()
            {
            }

            public function __isInitialized()
            {
            }
        };

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Doctrine proxy class "%s" hasn\'t parent class', get_class($obj)));

        $this->extractor->getProperties(get_class($obj));
    }

    /**
     * @throws ReflectionException
     */
    public function testNonProxy()
    {
        $this->assertSame(['foo', 'bar', 'baz'], $this->extractor->getProperties(Entity::class));
    }

    protected function setUp(): void
    {
        $this->innerExtractor = new PropertiesExtractor();
        $this->extractor = new DoctrineProxyPropertiesExtractor($this->innerExtractor);
    }
}
