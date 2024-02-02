<?php

namespace App\Jobs;

use App\Helpers\FirebaseHelper;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendScheduledMCQNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userRecords;
    protected $message;

    public function __construct($userRecords, $message)
    {
        $this->userRecords = $userRecords;
        $this->message = $message;
    }

    public function handle()
    {
        $user_data = User::whereIn('id', $this->userRecords)
            ->where('push_notification', 1)
            ->select('id', 'fcm_token')
            ->get();

        $all_tokens = [];
        $targetId = [];

        foreach ($user_data as $val) {
            if (!empty($val->fcm_token)) {
                array_push($all_tokens, $val->fcm_token);
                array_push($targetId, $val->id);
            }
        }

        if (count($all_tokens) > 0) {
            $token = $all_tokens;
        } else {
            return "Not have any fcm token";
        }

        $notification_data = [
            'action_type' => 0,
            'action_id' => $targetId,
            'title' => 'Auricle',
            'body' => $this->message,
        ];

        FirebaseHelper::CustomNotification($notification_data, $token);
    }
}
