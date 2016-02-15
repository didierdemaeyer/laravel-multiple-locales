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
        $this->output->newLine(1);

        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(4));
        $bar->start();

        // Variables
        $oldLocalesString = "'locale' => 'en',
    'locales' => ['en' => 'English', 'nl' => 'Dutch'],
    'skip_locales' => ['admin', 'api'],";
        $newLocalesString = "'locale' => 'en',";
        $pathRouteServiceProvider = __DIR__.'/../Providers/Original/RouteServiceProvider.php';
        $pathLanguageMiddleware = app_path('Http/Middleware/Language.php');
        $oldKernelString = "protected \$middleware = [
        \\App\\Http\\Middleware\\Language::class,";
        $newKernelString = "protected \$middleware = [";

        // Remove the 'locales' and 'skip_locales' arrays from config/app.php
        $this->info("Removing the 'locales' and the 'skip_locales' arrays from config/app.php");
        $this->helper->replaceAndSave(getcwd().'/config/app.php', $oldLocalesString, $newLocalesString);
        $bar->advance();

        // Setting the old RouteServiceProvider
        $this->info("Replacing the RouteServiceProvider with the old one...");
        $this->helper->copyFile($pathRouteServiceProvider, app_path('Providers/RouteServiceProvider.php'));
        $bar->advance();

        // Delete the Language middleware
        $this->info("Removing the Language middleware...");
        $this->helper->deleteFile($pathLanguageMiddleware);
        $bar->advance();

        // Remove the Language middleware from the Kernel
        $this->info("Removing the Language middleware from the Kernel...");
        $this->helper->replaceAndSave(getcwd().'/app/Http/Kernel.php', $oldKernelString, $newKernelString);
        $bar->advance();

        // Finished removing multiple locales from your project
        $bar->finish();
        $this->info("Finished removing multiple locales from your project.");

        $this->output->newLine(1);
    }
}