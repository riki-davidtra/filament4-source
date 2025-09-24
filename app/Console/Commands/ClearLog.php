<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:clear-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all Laravel log files in storage/logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');

        $files = File::glob($logPath . '/*.log');

        if (empty($files)) {
            $this->info('No log files found.');
            return;
        }

        foreach ($files as $file) {
            File::delete($file);
        }

        $this->info('All Laravel log files deleted.');
    }
}
