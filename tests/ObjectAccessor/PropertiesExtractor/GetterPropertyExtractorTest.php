<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\GetterPropertyExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetterPropertyExtractorTest extends TestCase
{
    public function test()
    {
        /** @var PropertiesExtractorInterface|MockObject $innerExtractor */
        $innerExtractor = $this->getMockBuilder(PropertiesExtractorInterface::class)
            ->onlyMethods(['getProperties'])
            ->getMockForAbstractClass()
        ;
        $innerExtractor->expects($this->any())->method('getProperties')->willReturn([]);

        $extractor = new GetterPropertyExtractor($innerExtractor);

        $obj = new class {
            public function getFoo()
            {
                return null;
            }

            public function isBar()
            {
                return false;
            }

            public function hasBaz()
            {
                return false;
            }
        };

        $this->assertSame(['foo', 'bar', 'baz'], $extractor->getProperties(get_class($obj)));
    }
}
