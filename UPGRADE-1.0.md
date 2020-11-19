# UPGRADE FROM 0.x to 1.0

1. If you used functions `is_initialized` or `is_typed`, you must install package `kenny1911/typed-properties-helper`
and replace namespace in `use` section to `Kenny1911\TypedProperties`.

2. If you use argument `$mapping` of `PopulateInterface::populate()` in your code, you must add argument
`$ignoreAttributes` as empty array (`[]`) between `$properties` and `$mapping`.

3. Fix building `PopulateInterface` instance.