<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VideoDownload;
use DB;

class DeleteOldVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete videos that are older than 7 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $date = now()->subDays(7)->toDateTimeString();
        DB::table('video_downloads')->where('created_at', '<', $date)->delete();
        $this->info('Delete Video.');
    }
}