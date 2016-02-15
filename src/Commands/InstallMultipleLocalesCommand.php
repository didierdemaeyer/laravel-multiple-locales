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
        $this->output->newLine(1);

        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(5));
        $bar->start();

        // Variables
        $localesString = "'locale' => 'en',
    'locales' => ['en' => 'English', 'nl' => 'Dutch'],
    'skip_locales' => ['admin', 'api'],";
        $pathRouteServiceProvider = __DIR__.'/../Providers/RouteServiceProvider.php';
        $pathLanguageMiddleware = __DIR__.'/../Middleware/Language.php';
        $kernelString = "protected \$middleware = [
        \\App\\Http\\Middleware\\Language::class,";

        // Add the 'locales' and 'skip_locales' to config/app.php
        $this->info("Adding 'locales' and 'skip_locales' to config/app.php");
        $this->helper->replaceAndSave(getcwd().'/config/app.php', "'locale' => 'en',", $localesString);
        $bar->advance();

        // Saving the user's RouteServiceProvider
        $this->info('Saving your RouteServiceProvider...');
        $this->helper->copyFile(app_path('Providers/RouteServiceProvider.php'), __DIR__.'/../Providers/Original/RouteServiceProvider.php');
        $bar->advance();

        // Replace the RouteServiceProvider
        $this->info('Replacing RouteServiceProvider...');
        $this->helper->copyFile($pathRouteServiceProvider, app_path('Providers/RouteServiceProvider.php'));
        $bar->advance();

        // Add the Language middleware
        $this->info('Adding Language middelware...');
        $this->helper->copyFile($pathLanguageMiddleware, app_path('Http/Middleware/Language.php'));
        $bar->advance();

        // Add the Language middleware to the Kernel for all requests
        $this->info('Adding Language middleware to app/Http/Kernel.php ...');
        $this->helper->replaceAndSave(getcwd().'/app/Http/Kernel.php', "protected \$middleware = [", $kernelString);
        $bar->advance();

        // Finished adding multiple locales to your project
        $bar->finish();
        $this->info('Finished adding multiple locales to your project.');

        $this->output->newLine(1);
    }
}