<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Helpers\Helper;
use Carbon\Carbon;
use DB;



class Subscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users when their subscription is about to expire.';

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
      $latestUserIds = Subscription::select('user_id')
    ->groupBy('user_id')
    ->orderBy('created_at', 'desc')
    ->pluck('user_id');

foreach ($latestUserIds as $latestUserId) {
    $user = Subscription::where('user_id', $latestUserId)->first();

    if ($user) {
        $expiry_date = Carbon::parse($user->expiry_date)->format('Y-m-d');
        $current_date = Carbon::now()->format('Y-m-d');
        $notificationDays = [2, 3, 5, 10]; // Days before expiry to send notifications

        $notifications = [];

        foreach ($notificationDays as $days) {
            $notification_date = Carbon::parse($expiry_date)->subDays($days)->format('Y-m-d');

            if ($notification_date === $current_date) {
                $message = "Your plan will expire in {$days} days.";
                $notifications[] = [
                    'user_id' => $user->user_id,
                    'message' => $message,
                    'type' => 3
                ];
            }
        }

        if ($expiry_date === $current_date) {
            $message = "Today Your plan has been expired.";
            $notifications[] = [
                'user_id' => $user->user_id,
                'message' => $message,
                'type' => 3
            ];
        }

        // Insert all notifications in one go
        if (!empty($notifications)) {
            DB::table('notifications')->insert($notifications);
        }

        // Notify users if required (assuming Helper::notify() sends the notifications)
        foreach ($notifications as $notification) {
            Helper::notify($notification['message'], $notification['user_id']);
        }
    }
}


    /*    $users = Subscription::whereDate('expiry_date','=' ,$fiveDaysAgo)->groupBy('user_id')->orderBy('id','desc')->get();
        $users = Subscription::whereDate('expiry_date','=' ,$threeDaysAgo)->groupBy('user_id')->orderBy('id','desc')->get();
        $users = Subscription::whereDate('expiry_date','=' ,$twoDaysAgo)->groupBy('user_id')->orderBy('id','desc')->get();
        $users = Subscription::whereDate('expiry_date','=' ,$today)->groupBy('user_id')->orderBy('id','desc')->get();*/


     

    }
}
