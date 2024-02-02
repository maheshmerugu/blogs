<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Reminder as Remind;
use App\Helpers\Helper;
use App\Helpers\FirebaseHelper;
use Carbon\Carbon;
use App\Models\ScheduleClass;
use DB;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //$message = "Your reminder";
        //$subscriptionCheck = Helper::notify($message);
       $reminders = Remind::all();

        foreach ($reminders as $reminder) {
            $notifyMinutes = $reminder->notify_me;
            $reminderDatetime = Carbon::parse($reminder->datetime);
            $userId = $reminder->user_id;
          // Get the current datetime in Y-m-d H:i format
        $currentDateTime = Carbon::now()->format('Y-m-d H:i');
        // Get the target datetime in Y-m-d H:i format
        $targetDateTime = $reminderDatetime->subMinutes($notifyMinutes)->format('Y-m-d H:i');
        // dd($targetDateTime);

         $message = "This reminder for $reminder->title.";
        // Check if the current datetime matches the target datetime
        if ($currentDateTime === $targetDateTime) {
               // Get the user based on user_id
                $user = User::find($userId);
                // dd($user);
               // Send the reminder notification to the user
                if ($user && $user->fcm_token) {
                    $insertNotification = DB::table('notifications')->insert([
                    'user_id'=>$userId,
                    'message'=>$message,
                    'type'=>4
                ]);
                    Helper::notifyScheduleClass([$user->id], $message);
                }
            }
        }

       // $this->info($users);
        // return 0;
    }
}