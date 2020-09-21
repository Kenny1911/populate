<?php /** @noinspection PhpLanguageLevelInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\Util;

use DateTime;
use Error;
use Kenny1911\Populate\Exception\RuntimeException;
use Kenny1911\Populate\Util\InitializedPropertiesTrait;
use PHPUnit\Framework\TestCase;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    class InitializedPropertiesTraitTest extends TestCase
    {
        private $obj;

        public function test()
        {
            $this->assertNull($this->obj->notTyped);
            $this->assertNull($this->obj->nullable);
            $this->assertSame(0, $this->obj->int);
            $this->assertSame(0.0, $this->obj->float);
            $this->assertSame('', $this->obj->string);
            $this->assertFalse($this->obj->bool);
            $this->assertSame([], $this->obj->array);
        }

        public function testPropertyNotExists()
        {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Property class@anonymous::$invalid does not exist');

            /** @noinspection PhpUndefinedFieldInspection */
            $this->obj->invalid;
        }

        public function testUnsetNotTypedProperty()
        {
            unset($this->obj->notTyped);

            $this->assertNull($this->obj->notTyped);
        }

        public function testPropertyNotInitialized()
        {
            $this->expectException(Error::class);
            $this->expectErrorMessage(
                sprintf('Typed property %s::$date must not be accessed before initialization', get_class($this->obj))
            );

            $this->obj->date;
        }

        protected function setUp(): void
        {
            $this->obj = new class
            {
                use InitializedPropertiesTrait;

                public $notTyped;

                public ?string $nullable;

                public int $int;

                public float $float;

                public string $string;

                public bool $bool;

                public array $array;

                public DateTime $date;
            };
        }
    }
}