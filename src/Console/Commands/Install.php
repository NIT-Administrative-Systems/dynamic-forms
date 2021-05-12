<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;

/**
 * @codeCoverageIgnore
 */
class Install extends GeneratorCommand
{
    public const FORMIOJS_VERSION = '^4.12.7';

    protected $signature = 'dynamic-forms:install';
    protected $description = 'Installs Dynamic Forms for Laravel';
    protected $type = 'Controller';

    public function handle()
    {
        $this->comment('Publishing file upload controller...');
        parent::handle();
        $this->newLine();

        $this->comment('Publishing JS assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'dynamic-forms-js']);
        $this->ejectJsInclude();
        $this->updatePackages(base_path('package.json'));
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
            file_get_contents(__DIR__.'/../../../stubs/routes.stub'),
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

    protected function updatePackages(string $packageFile): void
    {
        if (! file_exists($packageFile)) {
            return;
        }

        $packages = json_decode(file_get_contents($packageFile), true);

        $packages['devDependencies']['formiojs'] = self::FORMIOJS_VERSION;

        ksort($packages['devDependencies']);

        file_put_contents(
            $packageFile,
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}
