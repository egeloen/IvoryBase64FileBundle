# Usage

## Configuration

The main purpose of the bundle is to allow to upload base 64 encoded file through the form component.
By registering the bundle in the kernel, this feature is available but it is disabled by default in 
order to keep the default file type behavior. If you want to switch this behavior for all file types, 
you can configure the bundle with the following:

``` yaml
# app/config/config.yml
ivory_base64_file:
    default: true
```

## Form

The bundle introduces a new option called `base64` on the file type. If you don't configure the bundle 
to use the base 64 behavior by default, you will need to explicitly enable this option, otherwise, 
you don't need to specify it except if you want to disable the feature:
 
``` php
$builder->add('field', FileType::class, ['base64' => true]);
```

## Payload

The bundle needs a specific payload structure in order to work, the following is the minimal one:

``` json
{
    "field": {
        "name": "filename.png",
        "value": "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABh0lEQVQ4T23TO8iPYR..."
    }
}
```

If you want you can also provide more informations:

``` json
{
    "field": {
        "name": "filename.png",
        "value": "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABh0lEQVQ4T23TO8iPYR...",
        "mimeType": "image/png",
        "size": 12345
    }
}
```

## Technical details

Technically, an `Ivory\Base64FileBundle\Model\UploadedBase64File` will be created under the hood and 
then, populated into your model. This class extends the `Symfony\Component\HttpFoundation\File\UploadedFile` 
and create a regular file through the primitive `tmpfile`. That means your have a regular file in 
your temporary folder (/tmp) during the request lifecycle (the file is automatically removed at the end). 
Then, you can put file assertions on this field as well as moving the file where you want on your 
filesystem as you would do with a regular upload.
