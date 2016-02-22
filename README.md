README
======

When do I need this?
-------------------

If you are developing a composer package, and you want to know the location of
the project root and/or the vendor directory.

Can I do without?
-------------------

Sometimes you will work on your package stand alone, for instance on the unit
tests. In that case your vendor directory can be determined relatively to a
file.

You of course want your package to function when included via composer. Usually
you can determine the vendor directory by going up a couple of directories. But
don't forget to take the [vendor-dir]
(https://getcomposer.org/doc/06-config.md#vendor-dir) config option into account.

This package takes care of that. Even better: it calculates the directory
during composer install. So it's faster.

How do I use it?
----------------
```php
<?php
namespace MyVendor/MyApp;

use Hostnet/Component/Path/Path;

class ClassInNeed
{
    public function doSomethingWithPaths()
    {
        $vendor_dir = Path::VENDOR_DIR
        $base_dir   = Path::BASE_DIR
        // ...
    }
}
```

Installation
------------
Install using composer by using the command line interface
or adding the package name `hostnet/path-composer-plugin-lib`
to `composer.json`

```json
{
    "require": {
        "hostnet/path-composer-plugin-lib": "^1.0.0"
    }
}
```
