<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;

class DynamicFormsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ComponentRegistry::class, function ($app) {
            return new ComponentRegistry;
        });

        $this->app->singleton(S3Driver::class, function ($app) {
            $clientConfig = [
                'region' => config('filesystems.disks.s3.region', Arr::get($_ENV, 'AWS_DEFAULT_REGION')),
                'version' => 'latest',
            ];

            $url = config('filesystems.disks.s3.endpoint', Arr::get($_ENV, 'AWS_URL'));

            // When NOT running on Laravel Vapor, grab the AWS credentials:
            if (! isset($_ENV['AWS_LAMBDA_FUNCTION_VERSION'])) {
                $clientConfig['credentials'] = array_filter([
                    'key' => config('filesystems.disks.s3.key', Arr::get($_ENV, 'AWS_ACCESS_KEY_ID')),
                    'secret' => config('filesystems.disks.s3.secret', Arr::get($_ENV, 'AWS_SECRET_ACCESS_KEY')),
                    'token' => Arr::get($_ENV, 'AWS_SESSION_TOKEN'),
                ]);

                if (! is_null($url)) {
                    $clientConfig['url'] = $url;
                    $clientConfig['endpoint'] = $url;
                }
            }

            return new S3Driver($clientConfig, config('filesystems.disks.s3.bucket'));
        });
    }

    public function boot()
    {
        /** @var ComponentRegistry $registry */
        $registry = $this->app->make(ComponentRegistry::class);

        Request::macro('validateDynamicForm', function (string $definitionJson, string $submissionJson) {
            $formDefinition = new Form($definitionJson); // @TODO pass registry
            $validator = $formDefinition->validate($submissionJson);

            if (! $validator->isValid()) {
                throw (new ValidationException($validator))
                    ->redirectTo($this->session()->previousUrl());
            }

            return $validator->values();
        });
    }
}
