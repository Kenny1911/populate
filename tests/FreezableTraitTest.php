<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\Exception\FrozenException;
use Kenny1911\Populate\FreezableInterface;
use Kenny1911\Populate\FreezableTrait;
use PHPUnit\Framework\TestCase;

class FreezableTraitTest extends TestCase
{
    /** @var FreezableInterface */
    private $freezable;

    public function test()
    {
        $this->assertFalse($this->freezable->isFrozen());

        $this->freezable->freeze();

        $this->assertTrue($this->freezable->isFrozen());
    }

    public function testThrowFrozenException()
    {
        $this->expectException(FrozenException::class);
        $this->expectExceptionMessage('Frozen');
        $this->expectExceptionCode(100);

        $this->freezable->freeze();
        $this->freezable->throwFrozenExceptionPublic('Frozen', 100);
    }

    public function testThrowFrozenExceptionObjectNotFrozen()
    {
        $this->assertNull($this->freezable->throwFrozenExceptionPublic());
    }

    protected function setUp(): void
    {
        $this->freezable = new class implements FreezableInterface
        {
            use FreezableTrait;

            public function throwFrozenExceptionPublic(string $message = '', int $code = 0)
            {
                $this->throwFrozenException($message, $code);
            }
        };
    }
}
