# PHP Populate

`PHP Populate` is a library for fill attributes from source object to destination object.

## Basic components

- `PropertyAccessorInterface` - interface for read and write object attribute value. It has two implementations:
  - `ReflectionPropertyAccessor` - property accessor, based on `PHP Reflection API`.
  - `SymfonyPropertyAccessor` - bridge to `symfony/property-access` package.

- `ObjectAccessorInterface` - interface for export object properties to array and set object properties from data array.
  - `ObjectAccessor` - implementation of `ObjectAccessorInterface`.

- `FreezableInterface` - interface for freeze object.
  - `FreezableTrait` - implementation of `FreezableInterface`.

- `PopulateSettingsStorageInterface` - storage container of properties lists and mapping to populate objects. Used in
`AdvancedPopulate`.
  - `PopulateSettingsStorage` - simple implementation of `PopulateSettingsStorageInterface`.
  - `FreezablePopulateSettingsStorage` - decorator of `PopulateSettingsStorageInterface`, implementing
  `FreezableInterface`.

- `PopulateInterface` - interface for filling properties from source object to destination object.
  - `Populate` - simple implementation of `PopulateInterface`.
  - `AdvancedPopulate` - decorator of `PopulateInterface`, used `PopulateSettingsStorageInterface` as default settings.

- `PopulateBuilder` - builder for `PopulateInterface` object.

## Usage

### Create `Populate` object

Use `PopulateBuilder`.

```php
use Kenny1911\Populate\PopulateBuilder;

$populate = PopulateBuilder::create();
```

`PopulateBuilder` methods:

- `build` - Build new `PopulateInterface` object with set parameters.
- `setObjectAccessor` - Manual set `ObjectAccessorInterface`.
- `setPropertyAccessor` - Manual set `PropertyAccessorInterface`.
- `setReflectionPropertyAccessor` - Automatic create `ReflectionPropertyAccessor`.
- `setSymfonyPropertyAccessor` - Automatic create `SymfonyPropertyAccessor`. You can set original Symfony
`PropertyAccessorInterface` service.
- `setProperties` - Call method `setProperties` of `PopulateSettingsStorageInterface`.
- `setMapping` - Call method `setMapping` of `PopulateSettingsStorageInterface`.
- `freezeSettings` - Make `PopulateSettingsStorageInterface` freezable.

Manual creation of `Populate` object.

```php
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;

$populate = new Populate(
    new ObjectAccessor(
        new ReflectionPropertyAccessor()
    )
);
```

### Populate object

```php
use Kenny1911\Populate\PopulateBuilder;

$populate = PopulateBuilder::create()->build();

$src = new class {
    public $prop1 = 123;
    public $prop2 = 456;
};

$dest = new class {
    public $prop1;
    public $prop2;
};

$populate->populate($src, $dest);

// $dest->prop1 === 123
// $dest->prop2 === 456
````

### Get the associative array from object

```php
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;

$src = new class {
    public $prop1 = 123;
    public $prop2 = 456;
    public $prop3 = 789;
};

$accessor = new ObjectAccessor(
    new ReflectionPropertyAccessor()
);
$data = $accessor->getData($src); // $data = ['prop1' => 123, 'prop2' => 456, 'prop3' => 789]
```

```php
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\PropertyAccessor\SymfonyPropertyAccessor;

$src = new class {
    private $prop1 = 123;
    private $prop2 = 456;
    private $prop3 = 789;

    public function getProp1()
    {
        return $this->prop1;
    }
    
    public function getProp2()
    {
        return $this->prop2;
    }

    public function getProp3()
    {
        return $this->prop3;
    }
};

$accessor = new ObjectAccessor(
    new SymfonyPropertyAccessor() // Use Symfony property accessor from `symfony/property-access` package
);

$data = $accessor->getData($src, ['prop1', 'prop2'], ['prop1' => 'first', 'prop2' => 'second']);
// $data = ['first' => 123, 'second' = 456]

```
