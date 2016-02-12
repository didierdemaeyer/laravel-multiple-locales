<?php

namespace DidierDeMaeyer\MultipleLocales;

use Illuminate\Filesystem\Filesystem;

class MultipleLocalesHelper
{
    /**
     * The filesystem handler.
     * @var object
     */
    protected $files;

    /**
     * Create a new instance.
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Setting custom formatting for the progress bar.
     *
     * @param  object $bar Symfony ProgressBar instance
     *
     * @return object $bar Symfony ProgressBar instance
     */
    public function barSetup($bar)
    {
        // the finished part of the bar
        $bar->setBarCharacter('<comment>=</comment>');

        // the unfinished part of the bar
        $bar->setEmptyBarCharacter('-');

        // the progress character
        $bar->setProgressCharacter('>');

        // the 'layout' of the bar
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% ');

        return $bar;
    }

    /**
     * Open haystack, find and replace needles, save haystack.
     *
     * @param  string $oldFile The haystack
     * @param  mixed  $search  String or array to look for (the needles)
     * @param  mixed  $replace What to replace the needles for?
     * @param  string $newFile Where to save, defaults to $oldFile
     *
     * @return void
     */
    public function replaceAndSave($oldFile, $search, $replace, $newFile = null)
    {
        $newFile = ($newFile == null) ? $oldFile : $newFile;
        $file = $this->files->get($oldFile);
        $replacing = str_replace($search, $replace, $file);
        $this->files->put($newFile, $replacing);
    }

    /**
     * Move a file.
     *
     * @param $path
     * @param $target
     */
    public function copyFile($path, $target)
    {
        $this->files->copy($path, $target);
    }
}