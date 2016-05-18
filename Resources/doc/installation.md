# Installation

Require the bundle in your `composer.json` file:

```
$ composer require egeloen/base64-file-bundle
```

Register the bundle in your `AppKernel`:

``` php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        new Ivory\Base64FileBundle\IvoryBase64FileBundle(),
        // ...
    );
}
```
