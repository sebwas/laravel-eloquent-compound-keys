# Laravel Eloquent compound keys support

## Usage
In your Model that uses the compound keys, simply put `use SebWas\Laravel\Eloquent\CompoundKeys;`.
If another Model is referencing this through a _HasManyThrough_ relationship, use the `SebWas\Laravel\Eloquent\CompoundKeysRelationship` trait with it.

## Limits
There are a few limits to this, that can be solved by overriding the functions and re-implementing them properly.
- The static `destroy` can't be called
- The `morphTo` relationship won't work
- HasManyThrough's `getHasCompareKey` doesn't work
- Some other functions, especially on relations may be buggy
