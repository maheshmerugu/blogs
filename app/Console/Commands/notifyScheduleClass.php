<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\Helper;
use App\Helpers\FirebaseHelper;
use Carbon\Carbon;
use DB;
use App\Models\ScheduleClass;

class notifyScheduleClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the scheduled class';

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
          $currentDateTime = Carbon::now();
        $currentDate = $currentDateTime->format('Y-m-d');
        $fiveMinutesAfter = $currentDateTime->addMinutes(5); // Calculate the time after 5 minutes
        $currentTimeFormatted = $currentDateTime->format('H:i');
         $users = ScheduleClass::where('date', $currentDate)->where('start_time', $fiveMinutesAfter->format('H:i'))
            ->get();

            
            $userIds = $users->pluck('year_id')->toArray();
           $userRecords = User::whereIn('year_id', $userIds)->whereNotNull('fcm_token')->pluck('id')->toArray();
           
        $message ="This is a reminder for your live class starting soon.";
         foreach($userRecords as $user){
                      
                  if ($user) {
                            $insertNotification = DB::table('notifications')->insert([
                            'user_id'=>$user,
                            'message'=>$message,
                            'type'=>0
                        ]);
                  }
                 
             }
            $users = Helper::notifyScheduleClass($userRecords,$message);
    }
}
