# PHP Populate

`PHP Populate` is a library for fill attributes from source object to destination object.

For example, you can use this library to automatic fill properties from DTO to ORM entity.

## Install

```bash
composer require kenny1911/populate
```

## Usage

### Simple usage

To fill in an object's fields with values from another object, use method
`Kenny1911\Populate\PopulateInterface::populate()`. Arguments of method:

- `$src` - Source array or object from which values will be taken.
- `$dest` - Destination object.
- `$properties` - Array of allowed properties to be updated.
- `$ignoreProperties` - Array of denied properties not to be updated.
- `$mapping` - Key-value map to match property names from source object (`$src`) and destination object (`$dest`). Key -
property name in `$src`, value - in `$dest`.

Use `PopulateBuilder` for creating new `PopulateInterface` instance:

```php
use Kenny1911\Populate\PopulateBuilder;

$populate = PopulateBuilder::create()->build(); // Create new instance

class Src
{
    public $foo;
    public $bar;
    public $baz;
}

class Dest
{
    public $foo;
    public $bar;
    public $baz;
}

$src = new Src();
$src->foo = 'Foo';

$dest = new Dest();

$populate->populate(
    $src,               // Source object
    $dest,              // Destination object
    ['foo', 'bar'],     // Only properties `foo` and `bar` will be populated
    ['bar'],            // Property `bar` won't bw populated
    ['foo' => 'bar']    // Value of $src->foo will be set to $dest->bar
);

// $dest->bar === 'Foo';
```


### Advanced usage

You may need to use it with preset settings of arguments `$properties`, `$ignoreProperties` and `$mapping`. You can use
`AdvancedPopulate` for it:

```php
use Kenny1911\Populate\PopulateBuilder;

$settings = [
    [
        'src' => 'Src',                 // Required
        'dest' => 'Dest',               // Required
        'properties' => ['foo', 'bar'], // Optional
        'ignore_properties' => ['bar'], // Optional
        'mapping' => ['foo' => 'bar']   // Optional
    ]
];

$populate = PopulateBuilder::create()->setSettings($settings)->build();

class Src
{
    public $foo;
    public $bar;
    public $baz;
}

class Dest
{
    public $foo;
    public $bar;
    public $baz;
}

$src = new Src();
$src->foo = 'Foo';

$dest = new Dest();

$populate->populate($src, $dest);

// $dest->bar === 'Foo';
```

> Preset settings won't use if you will use `$properties` and `$ignoreProperties` arguments.

> If you set `$mapping` argument, it will merge with preset mapping.

### Integrate with Symfony

1. Register bundle in `config/bundles.php`:

    ```php
    return [
        // ...
        Kenny1911\Populate\Bridge\Symfony\PopulateBundle::class => ['all' => true]
        // ...
    ];
    ```

2. Create file `config/packages/populate.yaml`. Example:

    ```yaml
    populate:
        settings:
            -   src: Src
                dest: Dest
                properties: [foo, bar]
                ignore_properties: [bar]
                mapping:
                    foo: bar
    ```

Now, you can inject `PopulateInterface` to your own services.

```php
use Kenny1911\Populate\PopulateInterface;

class Service
{
    /** @var PopulateInterface */
    private $populate;

    public function __construct(PopulateInterface $populate)
    {
        $this->populate = $populate;
    }

    public function action($src, $dest)
    {
        $this->populate->populate($src, $dest);
    }
}
```

Also, you can use public symfony service `populate`:

```php
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Service implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function action($src, $dest)
    {
        $populate = $this->container->get('populate');

        $populate->populate($src, $dest);
    }
}
``` 