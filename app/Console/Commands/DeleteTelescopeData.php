<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteTelescopeData extends Command
{
    protected $signature = 'telescope:delete';
    protected $description = 'Delete old Telescope data';

    public function handle()
    {
        $oneDayAgo = now()->subDay(); // Get the date/time one day ago

        DB::table('telescope_entries')
           // ->where('created_at', '<', $oneDayAgo)
            ->delete();

        $this->info('Telescope data older than 1 day has been deleted.');
    }

}
