<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus activity logs yang lebih dari 30 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subDays(30))->delete();
        $this->info("Berhasil menghapus {$deleted} log lama.");
    }
}
