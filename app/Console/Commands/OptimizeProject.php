<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and optimize common Laravel caches including Filament.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running optimization commands...');

        $commands = [
            'view:clear',
            'route:clear',
            'config:clear',
            'cache:clear',
            'filament:optimize',
        ];

        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('All done! Laravel project optimized.');
    }
}
