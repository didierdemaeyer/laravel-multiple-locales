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
    private $pathOriginalProviders;

    /**
     * @var string
     */
    private $pathOriginalRouteServiceProvider;

    /**
     * @var string
     */
    protected $regexLocalesString = "/'locales' => \[.*],/";

    /**
     * @var string
     */
    protected $regexSkipLocalesString = "/'skip_locales' => \[.*],/";

    /**
     * @var string
     */
    protected $newLocalesString = "";

    /**
     * @var string
     */
    protected $newSkipLocalesString = "";

    /**
     * @var string
     */
    protected $oldKernelString = "
        \\App\\Http\\Middleware\\Language::class,";

    /**
     * @var string
     */
    protected $newKernelString = "";

    public function __construct(MultipleLocalesHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
        $this->config = json_decode(json_encode(config('multiple-locales')));

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
        // If the package is not installed, abort removal
        if ( ! $this->packageIsInstalled()) {
            return $this->abortRemoval();
        }

        $this->startProgressBar();

        $this->removeConfigArrays();
        $this->replaceRouteServiceProvider();
        $this->removeLanguageMiddleware();
        $this->unregisterLanguageMiddleware();

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
     * Abort the package removal.
     *
     * @return bool
     */
    private function abortRemoval()
    {
        $this->output->newLine(1);
        $this->error('The multiple locales package is not installed!');
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
        $this->bar = $this->helper->barSetup($this->output->createProgressBar(4));
        $this->bar->start();
    }

    /**
     * Remove the 'locales' and 'skip_locales' arrays from the app config.
     *
     * @return void
     */
    private function removeConfigArrays()
    {
        $this->info("Removing the 'locales' and 'skip_locales' arrays from config/app.php");
        $this->helper->pregReplaceAndSave($this->config->paths->config->app, $this->regexLocalesString, $this->newLocalesString);
        $this->helper->pregReplaceAndSave($this->config->paths->config->app, $this->regexSkipLocalesString, $this->newSkipLocalesString);
        $this->bar->advance();
    }

    /**
     * Replace the project's RouteServiceProvider.
     *
     * @return void
     */
    private function replaceRouteServiceProvider()
    {
        $this->info("Replacing the RouteServiceProvider...");
        $this->helper->moveFile($this->pathOriginalRouteServiceProvider, $this->config->paths->project->RouteServiceProvider);
        $this->helper->removeDir($this->pathOriginalProviders);
        $this->helper->replaceAndSave($this->config->paths->project->RouteServiceProvider, "", "");    // without saving the RouteServiceProvider was not registered
        $this->bar->advance();
    }

    /**
     * Remove the Language middleware.
     *
     * @return void
     */
    private function removeLanguageMiddleware()
    {
        $this->info("Removing the Language middleware...");
        $this->helper->deleteFile($this->config->paths->project->LanguageMiddleware);
        $this->bar->advance();
    }

    /**
     * Unregister the Language middleware from the Http/Kernel.
     *
     * @return void
     */
    private function unregisterLanguageMiddleware()
    {
        $this->info("Unregistering the Language middleware from Http/Kernel.php...");
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
        $this->info("Finished removing the multiple locales package from your project.");
        $this->output->newLine(1);
    }
}