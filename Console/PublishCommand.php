<?php namespace Phpna\Themes\Console;

use Phpna\Support\Traits\ConsoleTrait;

class PublishCommand extends Command
{
    use ConsoleTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpna:theme:publish {--r|reboll} {--t|theme=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($theme = $this->option('theme')){
            $this->publish($theme);
        }else{
            $this->publishAll();
        }
    }

    /**
     * Publish all themes.
     */
    protected function publishAll()
    {
        foreach ($this->laravel['themes']->all() as $theme) {
            $this->publish($theme);
        }
    }

    /**
     * Publish theme.
     *
     * @param mixed $theme
     */
    protected function publish($theme)
    {
        $theme = $theme instanceof Theme ? $theme : $this->laravel['themes']->find($theme);
        if (!is_null($theme)) {
            $assetsPath = $theme->getPath($theme->getAssetsConfig('compiled'));

            $destinationPath = public_path(config('themes.path').'/'.$theme->getLowerName());
            if($this->option('reboll')){
                $this->laravel['files']->copyDirectory($destinationPath,$assetsPath);
            }else{
                $this->laravel['files']->copyDirectory($assetsPath,$destinationPath);
            }
            $this->block("Asset published from: <info>{$theme->getName()}</info>");
        }
    }
}
