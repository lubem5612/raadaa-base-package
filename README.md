# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/raadaa-partners/raadaa-base.svg?style=flat-square)](https://packagist.org/packages/raadaa-partners/raadaa-base)
[![Total Downloads](https://img.shields.io/packagist/dt/raadaa-partners/raadaa-base.svg?style=flat-square)](https://packagist.org/packages/raadaa-partners/raadaa-base)
![GitHub Actions](https://github.com/raadaa-partners/raadaa-base/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require raadaa-partners/raadaa-base
```

## Usage

### File Upload
This package has a file uploader out of the box for these drivers: local, azure and amazon s3. Obtain an instance of the file upload helper to start using the methods. The file upload 
helper has three methods `uploadFile`, `uploadOrReplaceFile` and `deleteFile`.
```php
$uploader = new UploadHelper();
```

#### Fresh File Upload

This will upload a new file without deleting existing one associated with a column storing the path to the file. This should be used when creating a new resource that has image upload.

```php
$response = $uploader->uploadFile($request->file('file_key'), 'folder_to_store_image');
if ($response['success']) {
     Blog::create([ 
        'title' => 'Welcome here', 
        'image' => $response['upload_url'],
     ]);
}
```
#### Editing or Replacing an Uploaded File

This will first delete an existing file if it exists and then upload a new file associated with a column storing the path to the file in a specified model table. This should be used when updating an existing resource. The args are the UploadedFile instance, the folder to store the new image,
the model which created the initial image and the column storing the path to the image to be replaced

```php
$user = User::find($id);
$response = $uploader->uploadOrReplaceFile($request->file('file_key'), 'folder_to_store_image', $user, 'image');
if ($response['success']) {
     $user->image = $response['upload_url'];
     $user->save();
}
```
it is assumed here that the users table has a column called `image` where the file path to an uploaded file is stored.

#### Deleting Uploaded File

This will delete an uploaded file using the file path stored in the associated table

```php
$response = $uploader->deleteFile('path_to_uploaded_file_from_associated_table_column');
if ($response['success']) {
    // do something when image is deleted successfully
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email lubem@raadaa.com instead of using the issue tracker.

## Credits

-   [Lubem Tser](https://github.com/raadaa-partners)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
