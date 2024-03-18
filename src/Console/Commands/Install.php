<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    public const FORMIOJS_VERSION = '5.0.0-rc.4';

    protected $signature = 'dynamic-forms:install';

    protected $description = 'Installs Dynamic Forms for Laravel';

    /**
     * @codeCoverageIgnore
     */
    public function handle()
    {
        $this->comment('Publishing file upload controller...');
        $this->call('dynamic-forms:installStorageController');
        $this->newLine();

        $this->comment('Publishing resource controller...');
        $this->call('dynamic-forms:installResourceController');
        $this->newLine();

        $this->comment('Publishing JS assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'dynamic-forms-js']);

        @mkdir(resource_path('sass'));

        $this->ejectJsInclude(resource_path('js/app.js'));
        $this->ejectCssInclude(resource_path('sass/app.scss'));
        $this->updatePackages(base_path('package.json'));
        $this->newLine();

        $this->comment('Publishing routes...');
        $this->ejectRoutes(base_path('routes/web.php'));
        $this->newLine();

        $this->info('Dynamic Forms for Laravel has been installed!');

        $this->newLine();
        $this->info('Please review the new controller and implement appropriate authorization rules.');
        $this->info('And remember to run npm install && npm run build');
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
            "import './formio';",
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

    protected function ejectCssInclude(string $appCssFile): void
    {
        file_put_contents(
            $appCssFile,
            "@import '/node_modules/formiojs/dist/formio.full.css';",
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
