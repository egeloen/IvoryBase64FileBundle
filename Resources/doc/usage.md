# Usage

## Form

The main purpose of the bundle is to allow to upload base64 encoded file through the form component.
By default, this feature is disabled and you need to enable it in your configuration:

``` yaml
# app/config/config.yml
ivory_base64_file:
    form: true
```

When doing so, a form extension has been attached to the form component which will introduce a new 
option called `base64` on the file type. By default, this option is set to false, so, if you want 
to send base64 encoded file, you will need to enable this option:
 
``` php
$builder->add('field', FileType::class, array('base64' => true));
```

That's all, your form type will now be able to deal with a base64 encoded value. 

Technically, an `Ivory\Base64FileBundle\Model\Base64File` will be created under the hood. This class 
extends the `Symfony\Component\HttpFoundation\File\File` and create a regular file through the 
primitive `tmpfile`. That mean you can put file assertion on this field as well as moving the file 
where you want on your filesystem as you would do with a regular upload.

## Doctrine

The bundle also provides a new Doctrine DBAL type allowing you to automatically save an 
`Ivory\Base64FileBundle\Model\Base64File` in a `BLOB` type and re-create an 
`Ivory\Base64FileBundle\Model\Base64File` from a `BLOB` type.

To enable this feature, you will need to register this new type in your configuration:

``` yaml
# app/config/config.yml
doctrine:
    dbal:
        types:
            base64_file: Ivory\Base64FileBundle\Doctrine\Type\Base64FileType
```

And, you will also need to register this new type programmatically in one of your bundle:
 
``` php
namespace Acme\DemoBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeDemoBundle extends Bundle
{
    public function boot()
    {
        Type::addType('base64_file', 'Ivory\Base64FileBundle\Doctrine\Type\Base64FileType');
    }
}
```

That's all, you can now use the `base64_file` type in your metadata.

## Serializer

The bundle also supports to serialize an `Ivory\Base64FileBundle\Model\Base64File` or a
`Symfony\Component\HttpFoundation\File\File` into a base64 encoded representation. By default,
this feature is disabled, so if you want to use it you need to explicitly enable it:

``` yaml
# app/config/config.yml
ivory_base64_file:
    serializer: true
```

That's all, the serializer will now convert your file into a base64 representation.
