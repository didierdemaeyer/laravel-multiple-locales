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

    public function __construct(MultipleLocalesHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Config variables
        $pathAppConfig = getcwd() . '/config/app.php';
        $oldLocaleString = "'locale' => 'en',";
        $newLocalesString = "'locale' => 'en',
    'locales' => ['en' => 'English', 'nl' => 'Dutch'],
    'skip_locales' => ['admin', 'api'],";

        // Providers Variables
        $pathPackageRouteServiceProvider = __DIR__ . '/../Providers/RouteServiceProvider.php';
        $pathProjectRouteServiceProvider = app_path('Providers/RouteServiceProvider.php');

        // Middleware variables
        $pathOriginalProviders = __DIR__ . '/../Providers/Original';
        $pathOriginalRouteServiceProvider = __DIR__ . '/../Providers/Original/RouteServiceProvider.php';
        $pathPackageLanguageMiddleware = __DIR__ . '/../Middleware/Language.php';
        $pathProjectLanguageMiddleware = app_path('Http/Middleware/Language.php');

        // Kernel variables
        $pathKernel = getcwd() . '/app/Http/Kernel.php';
        $oldKernelString = "protected \$middleware = [";
        $newKernelString = "protected \$middleware = [
        \\App\\Http\\Middleware\\Language::class,";


        // If the user has multiple locales installed
        if (file_exists($pathOriginalRouteServiceProvider)) {
            $this->output->newLine(1);
            $this->error('The multiple locales package is already installed!');
            $this->output->newLine(1);

            return true;
        }

        $this->output->newLine(1);

        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(5));
        $bar->start();

        // Add the 'locales' and 'skip_locales' to config/app.php
        $this->info("Adding 'locales' and 'skip_locales' to config/app.php");
        $this->helper->replaceAndSave($pathAppConfig, $oldLocaleString, $newLocalesString);
        $bar->advance();

        // Saving the user's RouteServiceProvider
        $this->info('Saving your RouteServiceProvider...');
        $this->helper->makeDir($pathOriginalProviders);   // make the directory if it doesn't exist
        $this->helper->copyFile($pathProjectRouteServiceProvider, $pathOriginalRouteServiceProvider);
        $bar->advance();

        // Replace the RouteServiceProvider
        $this->info('Replacing RouteServiceProvider...');
        $this->helper->copyFile($pathPackageRouteServiceProvider, $pathProjectRouteServiceProvider);
        $bar->advance();

        // Add the Language middleware
        $this->info('Adding Language middelware...');
        $this->helper->copyFile($pathPackageLanguageMiddleware, $pathProjectLanguageMiddleware);
        $bar->advance();

        // Add the Language middleware to the Kernel for all requests
        $this->info('Adding Language middleware to app/Http/Kernel.php ...');
        $this->helper->replaceAndSave($pathKernel, $oldKernelString, $newKernelString);
        $bar->advance();

        // Finished adding multiple locales to your project
        $bar->finish();
        $this->info('Finished adding multiple locales to your project.');

        $this->output->newLine(1);
    }
}