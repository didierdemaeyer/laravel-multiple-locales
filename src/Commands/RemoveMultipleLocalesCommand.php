<?php

namespace DidierDeMaeyer\MultipleLocales\Commands;

use Illuminate\Console\Command;
use DidierDeMaeyer\MultipleLocales\MultipleLocalesHelper;

class RemoveMultipleLocalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiple-locales:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the Laravel Multiple Locales package';

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
        $regexLocalesString = "/'locales' => \[.*],/";
        $newLocalesString = "";
        $regexSkipLocalesString = "/'skip_locales' => \[.*],/";
        $newSkipLocalesString = "";

        // Providers variables
        $pathOriginalProviders = __DIR__ . '/../Providers/Original';
        $pathOriginalRouteServiceProvider = __DIR__ . '/../Providers/Original/RouteServiceProvider.php';
        $pathProjectRouteServiceProvider = app_path('Providers/RouteServiceProvider.php');

        // Middleware variables
        $pathLanguageMiddleware = app_path('Http/Middleware/Language.php');

        // Kernel variables
        $pathKernel = getcwd() . '/app/Http/Kernel.php';
        $oldKernelString = "
        \\App\\Http\\Middleware\\Language::class,";
        $newKernelString = "";


        // If the user does not have multiple locales installed
        if ( ! file_exists($pathOriginalRouteServiceProvider)) {
            $this->output->newLine(1);
            $this->error('The multiple locales package is not installed!');
            $this->output->newLine(1);

            return true;
        }

        $this->output->newLine(1);

        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(4));
        $bar->start();

        // Remove the 'locales' and 'skip_locales' arrays from config/app.php
        $this->info("Removing the 'locales' and the 'skip_locales' arrays from config/app.php");
        $this->helper->pregReplaceAndSave($pathAppConfig, $regexLocalesString, $newLocalesString);
        $this->helper->pregReplaceAndSave($pathAppConfig, $regexSkipLocalesString, $newSkipLocalesString);
        $bar->advance();

        // Setting the old RouteServiceProvider
        $this->info("Replacing the RouteServiceProvider with the old one...");
        $this->helper->moveFile($pathOriginalRouteServiceProvider, $pathProjectRouteServiceProvider);
        $this->helper->removeDir($pathOriginalProviders);
        $this->helper->replaceAndSave($pathProjectRouteServiceProvider, "", "");    // without saving the RouteServiceProvider was not registered
        $bar->advance();

        // Delete the Language middleware
        $this->info("Removing the Language middleware...");
        $this->helper->deleteFile($pathLanguageMiddleware);
        $bar->advance();

        // Remove the Language middleware from the Kernel
        $this->info("Removing the Language middleware from the Kernel...");
        $this->helper->replaceAndSave($pathKernel, $oldKernelString, $newKernelString);
        $bar->advance();

        // Finished removing multiple locales from your project
        $bar->finish();
        $this->info("Finished removing multiple locales from your project.");

        $this->output->newLine(1);
    }
}