# Upgrading

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
