<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActionLog;

class CleanupActionLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action-logs:cleanup {--days=30 : Number of days to keep logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive and cleanup old action logs (default: older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Starting cleanup of action logs older than {$days} days...");

        $archivedCount = ActionLog::cleanupOldLogs();

        if ($archivedCount > 0) {
            $this->info("Successfully archived and cleaned up {$archivedCount} log entries.");
        } else {
            $this->info("No old logs found to cleanup.");
        }

        return Command::SUCCESS;
    }
}
