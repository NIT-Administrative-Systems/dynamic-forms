# Upgrading

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
