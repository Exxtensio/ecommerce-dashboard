<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Exception;

class RunComposerCommand extends Command
{
    protected $signature = 'composer:run {action} {name}';
    protected $description = 'Run a composer command from Laravel';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $action = $this->argument('action');
        $name = $this->argument('name');

        $composerPath = base_path('vendor/exxtensio/ecommerce-dashboard/composer.phar');

        if (File::exists($composerPath)) {
            $process = new Process(['php', $composerPath, $action, $name]);
            $process->setWorkingDirectory(base_path());
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } else {
            throw new Exception("composer.phar not found in path: $composerPath");
        }
    }
}
