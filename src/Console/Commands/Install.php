<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;

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

        $this->comment('Publishing JS/CSS assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'dynamic-forms-js']);
        $this->ejectJsInclude(resource_path('js/app.js'));
        $this->ejectCssInclude(resource_path('sass/app.scss'));
        $this->updatePackages(base_path('package.json'));
        $this->newLine();

        $this->comment('Publishing routes...');
        $this->ejectRoutes(base_path('routes/web.php'),);
        $this->newLine();

        $this->info('Dynamic Forms for Laravel has been installed!');

        $this->newLine();
        $this->info('Please review the new controller and implement appropriate authorization rules.');
        $this->info('And remember to run `yarn install and Laravel Mix!');
    }

    protected function getNameInput(): string
    {
        return 'DynamicFormsStorageController';
    }

    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/DynamicFormsStorageController.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http\Controllers';
    }

    protected function ejectRoutes(string $routesFile): void
    {
        file_put_contents(
            $routesFile,
            file_get_contents(__DIR__.'/../../../stubs/routes.stub'),
            FILE_APPEND
        );
    }

    protected function ejectJsInclude(string $appJsFile): void
    {
        file_put_contents(
            $appJsFile,
            "require('./formio');",
            FILE_APPEND
        );
    }

    protected function ejectCssInclude(string $appCssFile): void
    {
        file_put_contents(
          $appCssFile,
          "@import '~formiojs/dist/formio.full';",
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
