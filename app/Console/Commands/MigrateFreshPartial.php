<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class MigrateFreshPartial extends Command
{
    protected $signature   = 'migrate:fresh-partial {--seed : Run seeders after migration}';
    protected $description = 'Drop all tables except some, skip specific migrations and seeders';

    public function handle()
    {
        if (app()->environment('production')) {
            $this->warn(str_repeat('=', 60));
            $this->warn('⚠️ WARNING: You are about to run migrate:fresh-partial in PRODUCTION!');
            $this->warn('This will DROP tables (except the ones you skipped) and re-run migrations.');
            $this->warn(str_repeat('=', 60));

            if (! $this->confirm('Do you really want to continue?', false)) {
                $this->error('❌ Operation cancelled.');
                return 1;
            }
        }

        $skipDrop     = [
            'users',
            'sessions',
            'migrations',
            'password_reset_tokens',
        ];
        $skipSeeders    = [
            'UserSeeder',
        ];

        // DROPPING
        $this->newLine();
        $this->line(str_repeat('=', 50));
        $this->info('DROPPING');
        $this->line(str_repeat('=', 50));

        foreach ($skipDrop as $table) {
            $this->warn("  Skipped drop: $table");
        }

        $connection = DB::connection();
        $driver     = $connection->getDriverName();
        if ($driver === 'sqlite') {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = array_map(fn($table) => $table->name, $tables);
        } elseif ($driver === 'mysql') {
            $databaseName = DB::getDatabaseName();
            $tables       = DB::table('information_schema.tables')
                ->select('table_name')
                ->where('table_schema', $databaseName)
                ->get()
                ->pluck('table_name')
                ->toArray();
        } elseif ($driver === 'pgsql') {
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            $tables = array_map(fn($table) => $table->tablename, $tables);
        } else {
            throw new \Exception("Unsupported DB driver: $driver");
        }

        Schema::disableForeignKeyConstraints();
        foreach ($tables as $table) {
            if (!in_array($table, $skipDrop)) {
                Schema::drop($table);
                $this->line("  Dropped: $table");
            }
        }
        Schema::enableForeignKeyConstraints();

        if (Schema::hasTable('migrations')) {
            DB::table('migrations')->truncate();
        }

        // MIGRATING
        $this->newLine();
        $this->line(str_repeat('=', 50));
        $this->info('MIGRATING');
        $this->line(str_repeat('=', 50));

        $migrationFiles = glob(database_path('migrations/*.php'));
        sort($migrationFiles);

        foreach ($migrationFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $exists = DB::table('migrations')->where('migration', $filename)->exists();
            if ($exists) {
                $this->warn("  Skipped migration (already applied): $filename");
                continue;
            }

            try {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/' . basename($file),
                    '--force' => true,
                ]);
                $this->info("  Migrated: $filename");
            } catch (\Throwable $e) {
                $this->warn("  Skipped migration (error): $filename");
                DB::table('migrations')->insert([
                    'migration' => $filename,
                    'batch' => 1,
                ]);
            }
        }

        // SEEDING
        if ($this->option('seed')) {
            $this->newLine();
            $this->line(str_repeat('=', 50));
            $this->info('SEEDING');
            $this->line(str_repeat('=', 50));

            foreach ($skipSeeders as $seeder) {
                $this->warn("  Skipped seed: $seeder");
                config(["seeder.skip.{$seeder}" => true]);
            }
            Artisan::call('db:seed', [
                '--force' => true,
            ], $this->getOutput());
        }

        $this->newLine();
        $this->info('✅ All done.');
        return 0;
    }
}
