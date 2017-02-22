# README

[![Travis Build Status](https://travis-ci.org/egeloen/IvoryBase64FileBundle.svg?branch=master)](http://travis-ci.org/egeloen/IvoryBase64FileBundle)
[![AppVeyor Build Status](https://ci.appveyor.com/api/projects/status/tgfrgk40lhqebi85/branch/master?svg=true)](https://ci.appveyor.com/project/egeloen/ivorybase64filebundle)
[![Code Coverage](https://scrutinizer-ci.com/g/egeloen/IvoryBase64FileBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/egeloen/IvoryBase64FileBundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egeloen/IvoryBase64FileBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egeloen/IvoryBase64FileBundle/?branch=master)
[![Dependency Status](http://www.versioneye.com/php/egeloen:base54-file-bundle/badge.svg)](http://www.versioneye.com/php/egeloen:base64-file-bundle)

[![Latest Stable Version](https://poser.pugx.org/egeloen/base64-file-bundle/v/stable.svg)](https://packagist.org/packages/egeloen/base64-file-bundle)
[![Latest Unstable Version](https://poser.pugx.org/egeloen/base64-file-bundle/v/unstable.svg)](https://packagist.org/packages/egeloen/base64-file-bundle)
[![Total Downloads](https://poser.pugx.org/egeloen/base64-file-bundle/downloads.svg)](https://packagist.org/packages/egeloen/base64-file-bundle)
[![License](https://poser.pugx.org/egeloen/base64-file-bundle/license.svg)](https://packagist.org/packages/egeloen/base64-file-bundle)

## Overview

The bundle provides a way to upload base64 file transparently through the Symfony2 form component. It
adds a new form option named `base64` on the file type which, when enabled, will convert you base64 
input into a regular file. It is especially useful when you're using the [FOSRestBundle](http://symfony.com/doc/master/bundles/FOSRestBundle/index.html).

For example, the following payload will be handled by the bundle:

``` json
{
    "field": {
        "name": "filename.png",
        "value": "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABh0lEQVQ4T23TO8iPYR..."
    }
}
```

## Documentation

 1. [Installation](/Resources/doc/installation.md)
 2. [Usage](/Resources/doc/usage.md)

## Testing

The bundle is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**. To
execute the test suite, check the travis [configuration](/.travis.yml).

## Contribute

We love contributors! Ivory is an open source project. If you'd like to contribute, feel free to propose a PR! You
can follow the [CONTRIBUTING](/CONTRIBUTING.md) file which will explain you how to set up the project.

## License

The Ivory Base64 File Bundle is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
