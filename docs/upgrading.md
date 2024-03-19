# Upgrading

## v1.0.0
This version swaps to the Formiojs v5 release candidate and assumes Bootstrap v5 and FontAwesome 6 are in use. The package assumes Laravel Vite, which is the default, but older versions of the formio JS customizations were written with Mix in mind.

To upgrade, ensure you are using `formiojs 5.0.0-rc.4`. You may be able to use a newer RC, but rc4 is what dynamic-forms has been tested with.

The JS customizations have been updated. You should review the `dist/` folder against your own `resources/js/formio/` folder: [`dist/` in v1.0.0](https://github.com/NIT-Administrative-Systems/dynamic-forms/tree/v1.0.0/dist) and determine how to pull any customizations you made to the JS into the revised files.

## v0.15.0
This version changes the `ResourceInterface::submissions()` method signature. There is a new parameter, `$context`:

```php
public static function submissions(int $limit, int $skip, string $key, string $needle, ?array $context = []): array;
```

If you have implemented this interface, you should update your implementations.

The `ResourceController` stub has been updated to pass the request headers as context. You can [review the updated stub](https://github.com/NIT-Administrative-Systems/dynamic-forms/blob/develop/stubs/DynamicFormsResourceController.stub) and adopt it as-is if you have not changed it, or [check the diff](https://github.com/NIT-Administrative-Systems/dynamic-forms/commit/c6a295f13aea332a3a384f8a36faf21e1a459c43#diff-6fece68af8c7cc8d9a0016ee539fa9315ee6c9ef07e7827093f4cbd9d09deb01) and apply the changes manually.

## v0.8.0
This version adds support for the Hidden component. There should not be any BC breaks.

The `builder-sidebar.js` that comes with the package now includes the hidden component under the advanced section, albeit commented out. 

To add this to your builder, add `hidden: true`. This is optional and not all dynamic-forms implementations will want to offer this to users.

## v0.7.0
This version adds support for calculated values using JSONLogic. This is ideal for things like summing up numbers into a "total" field.

If you implement the `ComponentInterface`, there is a new parameter in the constructor. Similarly, if you are instantiating any components on your own, the paramater will need to be added.

In the `defaults.js` file, there is a change required to show the calculated value UI in the builder. Inside the `global` function, the `calculateValuePanel`'s `ignore` value should be changed to false. 

## v0.6.0
This version adjusted the `composer.json` constraint to make it compatible with Laravel 9. No changes are necessary. 

## v0.5.0
This version switches the default editor to Quill (and removes support for CKEditor)

To enable support, edit the `resources/js/formio/default.js` file. The `textarea` function has a section default editor swithc that from ckeditor to quill:

```js
       textarea: [
    {
        key: 'display',
        ignore: false,
        components: [
            { key: 'editor', defaultValue: 'quill', disabled: true }, // do not set hidden, it won't change to ckeditor if you do that
            { key: 'wysiwyg', ignore: true },
        ],
    },
    {
        key: 'data',
        ignore: false,
        components: [
            { key: 'inputFormat', defaultValue: 'html', disabled: true },
        ],
    }
],
```


## v0.4.0
This version adds support for JSONLogic conditionals.

To enable support, edit the `resources/js/formio/default.js` file. The `global` function has a section for conditionals (shown below) -- remove that item:

```js
{
    key: 'conditional',
    ignore: false,
    components: [
        {key: 'customConditionalPanel', ignore: true},
    ],
}
```

Run Laravel Mix to rebuild, and your form builder should have JSON conditional support.

## v0.3.0
This version adds support for uploading files directly to your server as an alternative to S3.

The controller actions for file uploads provided by the `HandlesDynamicFormsStorage` trait have changed. You should update your routes file to point at the new actions:

```php
Route::prefix('dynamic-forms')->name('dynamic-forms.')->group(function () {
    // Dummy route so we can use the route() helper to give formiojs the base path for this group
    Route::get('/')->name('index');

    Route::post('storage/s3', [Controllers\DynamicFormsStorageController::class, 'storeS3'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::get('storage/s3', [Controllers\DynamicFormsStorageController::class, 'showS3'])->name('S3-file-download');
    Route::get('storage/s3/{fileKey}', [Controllers\DynamicFormsStorageController::class, 'showS3'])->name('S3-file-redirect');

    Route::post('storage/url', [Controllers\DynamicFormsStorageController::class, 'storeURL'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::get('storage/url', [Controllers\DynamicFormsStorageController::class, 'showURL'])->name('url-file-download');
    Route::delete('storage/url', [Controllers\DynamicFormsStorageController::class, 'deleteURL']);
});
```

The builder offers an option to select file uploads to your server or to S3. If you would like to restrict this, add the new `MIX_STORAGE_DEFAULT_VALUE` to your `.env` files. Valid options are `s3` or `url`. You need to run your Mix build after changing this for it to take effect.
