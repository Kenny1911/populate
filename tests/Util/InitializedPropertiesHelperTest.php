<?php /** @noinspection PhpLanguageLevelInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\Util;

use InvalidArgumentException;
use RuntimeException;
use Kenny1911\Populate\Util\InitializedPropertiesHelper;
use PHPUnit\Framework\TestCase;
use stdClass;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    class InitializedPropertiesHelperTest extends TestCase
    {
        public function testIsInitialized()
        {
            $obj = new class {
                public $notTyped;
                public string $initialized = 'Foo';
                public string $notInitialized;
                protected string $protected = 'protected';
                protected string $protectedNotInitialized;
            };

            $this->assertTrue(InitializedPropertiesHelper::isInitialized($obj, 'notTyped'));
            $this->assertTrue(InitializedPropertiesHelper::isInitialized($obj, 'initialized'));
            $this->assertFalse(InitializedPropertiesHelper::isInitialized($obj, 'notInitialized'));
            $this->assertTrue(InitializedPropertiesHelper::isInitialized($obj, 'protected'));
            $this->assertFalse(InitializedPropertiesHelper::isInitialized($obj, 'protectedNotInitialized'));

            unset($obj->notTyped);
            unset($obj->initialized);
            $obj->notInitialized = 'Bar';

            $this->assertFalse(InitializedPropertiesHelper::isInitialized($obj, 'notTyped'));
            $this->assertFalse(InitializedPropertiesHelper::isInitialized($obj, 'initialized'));
            $this->assertTrue(InitializedPropertiesHelper::isInitialized($obj, 'notInitialized'));
        }

        public function testIsInitializedInvalidArgument()
        {
            $this->expectException(InvalidArgumentException::class);
            $this->expectErrorMessage('Argument $obj passed must be of the type object.');

            /** @noinspection PhpParamsInspection */
            InitializedPropertiesHelper::isInitialized('invalid', 'invalid');
        }

        public function testIsTyped()
        {
            $obj = new class {
                public $foo;
                public string $bar;
                public string $baz = 'Baz';
                protected $protected;
                protected string $protectedTyped = 'protected';
                protected string $protectedTypedNotInitialized;
            };

            $this->assertFalse(InitializedPropertiesHelper::isTyped($obj, 'foo'));
            $this->assertTrue(InitializedPropertiesHelper::isTyped($obj, 'bar'));
            $this->assertTrue(InitializedPropertiesHelper::isTyped($obj, 'baz'));
            $this->assertFalse(InitializedPropertiesHelper::isTyped($obj, 'protected'));
            $this->assertTrue(InitializedPropertiesHelper::isTyped($obj, 'protectedTyped'));
            $this->assertTrue(InitializedPropertiesHelper::isTyped($obj, 'protectedTypedNotInitialized'));
        }

        public function testIsTypedInvalidArgument()
        {
            $this->expectException(InvalidArgumentException::class);
            $this->expectErrorMessage('Argument $obj passed must be of the type object.');

            /** @noinspection PhpParamsInspection */
            InitializedPropertiesHelper::isTyped('invalid', 'invalid');
        }

        public function testPropertyNotExists()
        {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Property stdClass::$invalid does not exist');

            InitializedPropertiesHelper::isInitialized(new stdClass(), 'invalid');
        }
    }
}
