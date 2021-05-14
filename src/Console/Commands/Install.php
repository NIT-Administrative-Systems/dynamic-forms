<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Env;

/**
 * @codeCoverageIgnore
 */
class Install extends GeneratorCommand
{
    /** @var string */
    const STORAGE_S3 = 's3';

    /** @var string */
    const STORAGE_URL = 'multipart';

    protected $signature = 'dynamic-forms:install {--upload=s3}';
    protected $description = 'Installs Dynamic Forms for Laravel';
    protected $type = 'Controller';

    public function handle()
    {
        if(!in_array($this->option('upload'), array(self::STORAGE_S3, self::STORAGE_URL)))
        {
            $this->comment('Unknown upload type provided');
            return;
        }
        $this->comment('Publishing file upload controller...');
        parent::handle();
        $this->newLine();

        $this->comment('Publishing JS assets...');
        if($this->option('upload') == self::STORAGE_URL)
        {
            $this->ejectEnv('MIX_STORAGE_DEFAULT_VALUE=url');
        }
        if($this->option('upload') == self::STORAGE_S3)
        {
            $this->ejectEnv('MIX_STORAGE_DEFAULT_VALUE=s3');
        }
        $this->callSilent('vendor:publish', ['--tag' => 'dynamic-forms-js']);
        $this->ejectJsInclude();
        $this->newLine();

        $this->comment('Publishing routes...');
        $this->ejectRoutes();
        $this->newLine();

        $this->info('Dynamic Forms for Laravel has been installed!');

        $this->newLine();
        $this->info('Please review the new controller and implement appropriate authorization rules.');
        $this->info('And remember to run Laravel Mix!');
    }

    protected function getNameInput()
    {
        return 'DynamicFormsStorageController';
    }

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/DynamicFormsStorageController.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    protected function ejectRoutes()
    {
        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__ . '/../../../stubs/routes.stub'),
            FILE_APPEND
        );
    }

    protected function ejectJsInclude()
    {
        file_put_contents(
            resource_path('js/app.js'),
            "require('./formio');",
            FILE_APPEND
        );
    }

    protected function ejectEnv($env)
    {
        file_put_contents(
            base_path('.env'),
            $env,
            FILE_APPEND
        );
    }
}
