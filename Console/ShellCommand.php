<?php namespace Phpna\Themes\Console;

use Phpna\Support\Traits\ConsoleTrait;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ShellCommand extends Command
{
    use ConsoleTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpna:theme:shell {shell*} {--t|theme=}';

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
            $this->shell($theme);
        }else{
            $this->shellAll();
        }
    }

    protected function shellAll()
    {
        foreach ($this->laravel['themes']->all() as $theme) {
            $this->shell($theme->getLowerName());
        }
    }

    protected function shell($theme)
    {
        $process = new Process(trim(implode(' ',$this->argument('shell'))));
        $process->setWorkingDirectory(config('themes.path').'/'.$theme);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);
        $process->start();
        $this->block("start theme  <info>{$theme}'s</info> shell command!");
        $process->wait(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->error($buffer);
            } else {
                echo $buffer;
            }
        });
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }else{
            $this->block('run shell finish and everything is success!');
        }
    }
}
