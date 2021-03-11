<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WakeUpDatabase extends Command
{
    protected $signature = 'db:wake {retries=5 : attempts to make} {wait=5 : time to wait between retries, in seconds}';
    protected $description = 'Wakes up a potentially-inactive serverless RDS database';

    public function handle()
    {
        $retries = (int) $this->argument('retries');
        $wait_between = ((int) $this->argument('wait')) * 1000;

        retry($retries, fn () => DB::select('SELECT 1'), $wait_between);

        $this->info('Database is up');
    }
}
