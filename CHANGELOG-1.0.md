# CHANGELOG for 1.0.x

- Package will depend on:
    - `symfony/property-access` - used for getting and setting property values.
    - `symfony/property-info` - used for getting object properties list.

- Removed unused interfaces and implementations of it:
    - `Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface`.
    - `Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractorInterface`.

- Rename and restructure `SettingsStorageInterface` to `SettingsInterface`:
    - Move `SettingsInterface` to `Kenny1911\Populate\Settings` namespace.
    - Remove all setters from `SettingsInterface`. Now all settings set in object constructor.
    - Remove `Kenny1911\Populate\SettingsStorage\FreezableSettingsStorage`.
    - Remove unused `Kenny1911\Populate\Freezable\FreezableInterface`.

- Remove exception classes. Now used builtin php exceptions.

- Add argument `$ignoreProperties` to `PopulateInterface::populate()` and `ObjectAccessorInterface::getData()` methods.

- Add integration with symfony framework.

- Remove `Kenny1911\Populate\Util\InitializedPropertiesHelper` and functions `is_initialized` and `is_typed` from
`Kenny1911\Populate` namespace.
    