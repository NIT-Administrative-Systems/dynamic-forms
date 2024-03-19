<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DynamicForms\Console\Commands\Install;
use Northwestern\SysDev\DynamicForms\Console\Commands\InstallResourceController;
use Northwestern\SysDev\DynamicForms\Console\Commands\InstallStorageController;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;

class DynamicFormsProvider extends ServiceProvider
{
    /**
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->app->singleton(ComponentRegistry::class, function ($app) {
            return new ComponentRegistry;
        });

        $this->app->singleton(ResourceRegistry::class, function ($app) {
            return new ResourceRegistry();
        });

        $this->app->singleton(JSONLogicInitHelper::class, function ($app) {
            return new JSONLogicInitHelper;
        });

        $this->app->singleton(FileComponentRegistry::class, function ($app) {
            return new FileComponentRegistry;
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

    /**
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();

        /** @var ComponentRegistry $registry */
        $registry = $this->app->make(ComponentRegistry::class);
        /** @var JSONLogicInitHelper $jsonHelper */
        $jsonHelper = $this->app->make(JSONLogicInitHelper::class);
        /** @var FileComponentRegistry $fileRegistry */
        $fileRegistry = $this->app->make(FileComponentRegistry::class);

        Request::macro('validateDynamicForm', function (string $definitionJson, string $submissionJson, ?ResourceRegistry $resourceRegistry = null) {
            if (is_null($resourceRegistry)) {
                $resourceRegistry = resolve(ResourceRegistry::class);
            }

            $formDefinition = new Form($definitionJson, $resourceRegistry); // @TODO pass registry
            $validator = $formDefinition->validate($submissionJson);

            if (! $validator->isValid()) {
                /** @var Redirector $redirector */
                $redirector = resolve(Redirector::class);

                throw (new ValidationException($validator))
                    ->redirectTo($redirector->getUrlGenerator()->previous());
            }

            return $validator->values();
        });
    }

    /**
     * @codeCoverageIgnore
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
                InstallResourceController::class,
                InstallStorageController::class,
            ]);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../dist' => resource_path('js/formio'),
            ], 'dynamic-forms-js');
        }
    }
}
