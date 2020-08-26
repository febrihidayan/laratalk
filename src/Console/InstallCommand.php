<?php

namespace Laratalk\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laratalk:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the resources and run migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'laratalk-assets']);
        $this->callSilent('vendor:publish', ['--tag' => 'laratalk-config']);
        $this->callSilent('migrate');

        $this->info('Installation complete.');
    }
}
