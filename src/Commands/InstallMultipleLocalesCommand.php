<?php

namespace DidierDeMaeyer\MultipleLocales\Commands;

use Illuminate\Console\Command;
use DidierDeMaeyer\MultipleLocales\MultipleLocalesHelper;

class InstallMultipleLocalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiple-locales:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Multiple Locales package';

    /**
     * Helper class.
     *
     * @var object
     */
    protected $helper;

    /**
     * @var object
     */
    protected $config;

    /**
     * @var object
     */
    private $bar;

    /**
     * @var string
     */
    private $pathPackageRouteServiceProvider;

    /**
     * @var string
     */
    private $pathPackageLanguageMiddleware;

    /**
     * @var string
     */
    private $pathOriginalProviders;

    /**
     * @var string
     */
    private $pathOriginalRouteServiceProvider;

    /**
     * @var string
     */
    protected $oldLocalesString = "'locale' => 'en',";

    /**
     * @var string
     */
    protected $newLocalesString = "'locale' => 'en',
    'locales' => ['en' => 'English', 'nl' => 'Dutch'],
    'skip_locales' => ['admin', 'api'],";

    /**
     * @var string
     */
    protected $oldKernelString = "protected \$middleware = [";

    /**
     * @var string
     */
    protected $newKernelString = "protected \$middleware = [
        \\App\\Http\\Middleware\\Language::class,";

    public function __construct(MultipleLocalesHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
        $this->config = json_decode(json_encode(config('multiple-locales')));

        $this->pathPackageRouteServiceProvider = __DIR__ . '/../Providers/RouteServiceProvider.php';
        $this->pathPackageLanguageMiddleware = __DIR__ . '/../Middleware/Language.php';
        $this->pathOriginalProviders = __DIR__ . '/../Providers/Original';
        $this->pathOriginalRouteServiceProvider = __DIR__ . '/../Providers/Original/RouteServiceProvider.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If the package is already installed, abort installation
        if ($this->packageIsInstalled()) {
            return $this->abortInstallation();
        }

        $this->startProgressBar();

        $this->addConfigArrays();
        $this->saveOriginalRouteServiceProvider();
        $this->replaceRouteServiceProvider();
        $this->addLanguageMiddleware();
        $this->registerLanguageMiddleware();

        $this->stopProgressBar();
    }

    /**
     * Check if the package is currently installed.
     *
     * @return bool
     */
    private function packageIsInstalled()
    {
        if (file_exists($this->pathOriginalRouteServiceProvider)) {

            return true;
        }

        return false;
    }

    /**
     * Abort the package installation.
     *
     * @return bool
     */
    private function abortInstallation()
    {
        $this->output->newLine(1);
        $this->error('The multiple locales package is already installed!');
        $this->output->newLine(1);

        return true;
    }

    /**
     * Start the progress bar.
     *
     * @return void
     */
    private function startProgressBar()
    {
        $this->output->newLine(1);
        $this->bar = $this->helper->barSetup($this->output->createProgressBar(5));
        $this->bar->start();
    }

    /**
     * Add the 'locales' and 'skip_locales' arrays to the app config.
     *
     * @return void
     */
    private function addConfigArrays()
    {
        $this->info("Adding the 'locales' and 'skip_locales' arrays to config/app.php");
        $this->helper->replaceAndSave($this->config->paths->config->app, $this->oldLocalesString, $this->newLocalesString);
        $this->bar->advance();
    }

    /**
     * Save the project's original RouteServiceProvider.
     *
     * @return void
     */
    private function saveOriginalRouteServiceProvider()
    {
        $this->info('Saving the original RouteServiceProvider...');
        $this->helper->makeDir($this->pathOriginalProviders);   // make the directory if it doesn't exist
        $this->helper->copyFile($this->config->paths->project->RouteServiceProvider, $this->pathOriginalRouteServiceProvider);
        $this->bar->advance();
    }

    /**
     * Replace the project's RouteServiceProvider.
     *
     * @return void
     */
    private function replaceRouteServiceProvider()
    {
        $this->info('Replacing the RouteServiceProvider...');
        $this->helper->copyFile($this->pathPackageRouteServiceProvider, $this->config->paths->project->RouteServiceProvider);
        $this->bar->advance();
    }

    /**
     * Add the Language middleware.
     *
     * @return void
     */
    private function addLanguageMiddleware()
    {
        $this->info('Adding the Language middelware...');
        $this->helper->copyFile($this->pathPackageLanguageMiddleware, $this->config->paths->project->LanguageMiddleware);
        $this->bar->advance();
    }

    /**
     * Register the Language middleware in the Http/Kernel.
     *
     * @return void
     */
    private function registerLanguageMiddleware()
    {
        $this->info('Registering the Language middleware in Http/Kernel.php...');
        $this->helper->replaceAndSave($this->config->paths->project->Kernel, $this->oldKernelString, $this->newKernelString);
        $this->bar->advance();
    }

    /**
     * Stop the progress bar.
     *
     * @return void
     */
    private function stopProgressBar()
    {
        $this->bar->finish();
        $this->info('Finished adding the multiple locales package to your project.');
        $this->output->newLine(1);
    }
}