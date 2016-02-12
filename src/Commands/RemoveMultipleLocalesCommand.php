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
    protected $description = 'Remove the Laravel Multiple Locales package.';

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

        $this->info('Nothing yet.');

        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(4));
        $bar->start();

        // Variables
        $oldLocalesString = "'locale' => 'en',
    'locales' => ['en' => 'English', 'nl' => 'Dutch'],
    'skip_locales' => ['admin', 'api'],";
        $newLocalesString = "'locale' => 'en',";
        $pathRouteServiceProvider = __DIR__.'/../Providers/RouteServiceProvider.php';
        $pathLanguageMiddleware = __DIR__.'/../Middleware/Language.php';
        $kernelString = "protected \$middleware = [
        \\App\\Http\\Middleware\\Language::class,";

        // Remove the 'locales' and 'skip_locales' arrays from config/app.php
        $this->info("Removing the 'locales' and the 'skip_locales' arrays from config/app.php");
        $this->helper->replaceAndSave(getcwd().'/config/app.php', "'locale' => 'en',", $oldLocalesString, $newLocalesString);
        $bar->advance();

        $this->output->newLine(1);
    }
}