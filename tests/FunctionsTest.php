<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;
use function Kenny1911\Populate\is_initialized;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    class FunctionsTest extends TestCase
    {
        /** @noinspection PhpLanguageLevelInspection */
        public function test()
        {
            $obj = new class {
                public $notTyped = 123;
                public string $initialized = 'Initialized';
                public string $notInitialized;
            };

            $this->assertTrue(is_initialized($obj, 'notTyped'));
            $this->assertTrue(is_initialized($obj, 'initialized'));
            $this->assertFalse(is_initialized($obj, 'notInitialized'));

            $obj->notInitialized = 'foo';
            unset($obj->initialized);
            unset($obj->notTyped);

            $this->assertTrue(is_initialized($obj, 'notTyped'));
            $this->assertFalse(is_initialized($obj, 'initialized'));
            $this->assertTrue(is_initialized($obj, 'notInitialized'));
        }

        public function testPropertyNotExists()
        {
            $this->expectException(RuntimeException::class);
            $this->expectErrorMessage('Property class@anonymous::$invalid does not exist');

            is_initialized(new class {}, 'invalid');
        }
    }
}