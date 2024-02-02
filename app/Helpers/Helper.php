<?php

namespace App\Helpers;

use App\Helpers\FirebaseHelper;
use App\Models\PlanSubscription as Plan;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\User;
use App\Models\VideoPlayStatus;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Helper
{

    /**
     * @return otp for mobile
     */
    public static function generateOtp()
    {

        $randomOtp = mt_rand(1000, 9999);

        return $randomOtp ? $randomOtp : 1234;
    }

    /**
     * @return otp for email
     */
    public static function sendMailUser($data, $subject, $name = '', $email)
    {
        dd($data);
        Mail::send($data, function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
                ->subject($subject);
            $message->from(config('mail.username'), env('APP_NAME'));
        });

        // check for failures
        if (Mail::failures()) {

            return false;
        }

        return true;
    }

    //s3 image upload
    public static function uploadFileToS3($file, $dir)
    {

        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $dir . $imageName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        //Storage::disk('s3')->url($filePath);
        return $imageName;
    }
    //delete s3 image
    public static function deleteFileToS3($file, $dir)
    {
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $dir . $imageName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        //Storage::disk('s3')->url($filePath);
        return $filePath;
    }

    /*
     *calculate subscription watch hour and months for videos
     *subcription_for (1=>Video,2=>Notes)
     */
    public static function checkUserSubscriptionForVideo()
    {

        //Get subscription data of the user
        $subscription = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

        if (isset($subscription)) {
            $plan_check = Plan::where('id', $subscription->plan_id)->first();
            if (!isset($plan_check)) {
                $response = [
                    'code' => 2,
                    'status' => 420,
                    'message' => "Plan is no active.",
                    'data' => (object) [],
                ];
                return json_encode($response);
            }
        }

        if ($subscription == null) {

            $response = [
                'code' => 2,
                'status' => 420,
                'message' => "Subscribe to our plan now.",
                'data' => (object) [],
            ];
            return json_encode($response);
        }

        if (!empty($subscription)) {
            //  print_r($subscription); die;
            //Check plan which plan purchase by the user
            $plan_check = Plan::where('id', $subscription->plan_id)->first();
            //Get how much time user watch the video (in second).
            $video_used_seconds = VideoPlayStatus::where(['subcription_id' => $subscription->id, 'user_id' => auth('api_user')->user()->id])->pluck('watch_time')->sum();
            //dd($subscription);
            // dd($subscription);
            //Convert second to hour
            $currentDate = Carbon::now();
            /*$day = floor($video_used_seconds / 86400);
            $hours = floor(($video_used_seconds -($day*86400)) / 3600);
             */
            $seconds = $video_used_seconds;
            $hours = floor($seconds / 3600);

            if (isset($plan_check)) {
                $plan_watch_hour = $plan_check->watch_hours;
            } else {
                $plan_watch_hour = 0;
            }
            //Check watch hour and months complete or not
            // if (round($hours) >= $plan_check->watch_hours || $subscription->expiry_date->toDateString() == $currentDate->toDateString()) {
            if (round($hours) >= $plan_check->watch_hours || Carbon::now()->greaterThan($subscription->expiry_date)) {

                $response = [
                    'code' => 0,
                    'status' => 420,
                    'message' => "Your watch hour or months are completed. Please subscribe the our plan.",
                    'data' => (object) [],
                ];
                $data = json_encode($response);

                return $data;
                die;
            } else {

                $response = [
                    'code' => 3,
                    'status' => 200,
                    'message' => "",
                    'data' => (object) [],
                ];
                return json_encode($response);

            }

        }
    }
    /*
     *calculate subscription date for notes
     *subcription_for (1=>Video,2=>Notes)
     */

    public static function checkUserSubscriptionForNotes()
    {
        //Get subscription data of the user
        $subscription = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 2])->orderBy('id', 'desc')->first();
        if ($subscription == null) {

            $response = [
                'code' => 2,
                'status' => 420,
                'message' => "To see notes you have to buy a plan.",
                'data' => (object) [],
            ];
            return json_encode($response);
        }
        // $currentDate = Carbon::now();
        if (!empty($subscription)) {
            //Check months complete or not
            if (Carbon::now()->greaterThan($subscription->expiry_date)) {
                $response = [
                    'code' => 0,
                    'status' => 420,
                    'message' => "To see notes you have to buy a plan.",
                    'data' => (object) [],
                ];

                $data = json_encode($response);
                return $data;
                die;
            }
        }
    }

/*
 *calculate subscription date for notes
 *subcription_for (1=>Video,2=>Notes)
 */

    public static function checkUserDevice()
    {
        //Get subscription data of the user
        $new_device_id = User::where('id', auth('api_user')->user()->id)->pluck('new_device_id')->first();
        $device_id = DB::table('user_log_history')->where(['user_id' => auth('api_user')->user()->id])->pluck('device_id')->first();

        if ($new_device_id != $device_id) {
            $logout = auth('api_user')->user()->token()->revoke();
            if ($logout) {
                $response = [
                    'code' => 0,
                    'status' => 200,
                    'message' => "Unauthenticated.",
                    'data' => (object) [],
                ];
                return response()->json($response, 401);
            }
        }
    }

    //Send hsp sms to phone
    // public static function send_mobile_otp($mobile)
    // {
    //     //OTP
    //     $otp = rand(1231, 7879);
    //     $message = urlencode('Your Auricle app login OTP is ' . $otp . ' . Thank you Team "Auricle"');
    //     // other parametes might also contain spaces ...
    //     $username = urlencode('gowthamsiva');
    //     $sendername = urlencode('AURICL');
    //     $mobile_number = str_replace('+91', '', $mobile);
    //     //etc

    //     $url = 'http://sms.hspsms.com/sendSMS?username=' . $username
    //         . '&message=' . $message
    //         . '&sendername=' . $sendername
    //         . '&smstype=TRANS&numbers=' . $mobile_number . '&apikey=ec0c5fb3-6177-4e98-afed-dfb76bc9e938';

    //     if ($url) {
    //         file_get_contents($url); //for debugging
    //         return $otp;
    //     } else {
    //         echo "Failure";
    //     }
    // }

    //send msg91 msg
    public static function send_mobile_otp($mobile)
    {
        // OTP
        $otp = rand(1231, 7879);

        $curl = curl_init();
        $url = "https://control.msg91.com/api/v5/otp?template_id=65156e00d6fc0508c44bfa32&mobile=" . $mobile . "&otp=" . $otp . "&otp_length=4";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"Param1":"1234"}',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'authkey: 406363AzMgDTNdZ650d3803P1',
                'content-type: application/json',
                'Cookie: PHPSESSID=bde5ojltd2n5oaccmajdmavgo1',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        if ($url) {
            // file_get_contents($url); //for debugging
            return $otp;
        } else {
            echo "Failure";
        }

    }

    public static function UploadDoc($destinationPath, $field, $newName = '')
    {
        $directory = public_path('uploads/admin/user_csv'); // Change to your desired directory path
        $files = File::allFiles($directory);

        if ($files) {
            foreach ($files as $file) {
                if ($file->getExtension() === 'csv') {
                    unlink($file->getPathname());
                }
            }
        }

        $destinationPath = $destinationPath;

        $extension = $field->getClientOriginalExtension();
        $fileName = time() . '-' . rand(1, 999) . '.' . $extension;
        // dd($fileName);
        $field->move($destinationPath, $fileName);

        return $destinationPath . '/' . $fileName;
    }

    // public static function UploadDoc($destinationPath, $field, $newName = '')
    // {
    //     $directory = public_path('uploads/admin/user_csv'); // Change to your desired directory path
    //     $files = File::allFiles($directory);

    //     if($files)
    //     {
    //          foreach ($files as $file) {
    //         if ($file->getExtension() === 'csv') {
    //             unlink($file->getPathname());
    //         }
    //     }
    //     }

    //     $destinationPath = $destinationPath;

    //     $extension = $field->getClientOriginalExtension();
    //     $fileName = time() . '-' . rand(1, 999) . '.' . $extension;
    //     // dd($fileName);
    //     $field->move($destinationPath, $fileName);

    //     return $fileName;
    // }

    //custom question bank data
    public static function getQuestions($userId, $questionType, $year = "", $difficulty_level = "")
    {
        //code start

        $unattemptedQuestions = DB::table('question_banks')
            ->leftJoin('q_b_analytics', function ($join) use ($userId) {
                $join->on('question_banks.id', '=', 'q_b_analytics.qb_id')
                    ->where('q_b_analytics.user_id', '=', $userId);
            })
            ->select('question_banks.*', DB::raw('NULL as answer'))
            ->where('question_banks.year_id', $year)
            ->where('question_banks.difficulty_level_id', $difficulty_level)
        //->whereNull('custom_q_b_analytics.user_id')
            ->whereNull('q_b_analytics.user_id')
            ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.subject_id')
            ->toArray();

        $incorrectlyAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->where('question_banks.year_id', $year)
            ->where('question_banks.difficulty_level_id', $difficulty_level)
            ->where('q_b_analytics.is_answer', '=', 'false')
            ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.subject_id')
            ->toArray();

        $attemptedAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->where('question_banks.year_id', $year)
            ->where('question_banks.difficulty_level_id', $difficulty_level)
            ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.subject_id')
            ->toArray();

        $queryConditions = [];
        foreach ($questionType as $condition) {
            switch ($condition) {
                case 'attempted':
                    $queryConditions[] = $attemptedAnsweredQuestions;
                    break;
                case 'unattempted':
                    $queryConditions[] = $unattemptedQuestions;
                    break;
                case 'incorrect':
                    $queryConditions[] = $incorrectlyAnsweredQuestions;
                    break;
                default:
                    break;
            }
        }
        return $queryConditions;

    }
    public static function getQuestions1($userId, $questionType, $subjects)
    {
        //code start
        $unattemptedQuestions = DB::table('question_banks')
            ->leftJoin('q_b_analytics', function ($join) use ($userId) {
                $join->on('question_banks.id', '=', 'q_b_analytics.qb_id')
                    ->where('q_b_analytics.user_id', '=', $userId);
            })
            ->select('question_banks.*', DB::raw('NULL as answer'))
            ->whereNull('q_b_analytics.user_id')
            ->whereIn('question_banks.subject_id', $subjects)
            ->pluck('question_banks.id')
            ->toArray();

        $incorrectlyAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->whereIn('question_banks.subject_id', $subjects)
            ->where('q_b_analytics.is_answer', '=', 'false')
        // ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.id')
            ->toArray();

        $attemptedAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->whereIn('question_banks.subject_id', $subjects)
        // ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.id')
            ->toArray();

        $queryConditions = [];
        foreach ($questionType as $condition) {
            switch ($condition) {
                case 'attempted':
                    $queryConditions[] = $attemptedAnsweredQuestions;
                    break;
                case 'unattempted':
                    $queryConditions[] = $unattemptedQuestions;
                    break;
                case 'incorrect':
                    $queryConditions[] = $incorrectlyAnsweredQuestions;
                    break;
                default:
                    break;
            }
        }
        return $queryConditions;

    }
    public static function getQuestions2($userId, $questionType, $module)
    {
        //code start
        $unattemptedQuestions = DB::table('question_banks')
            ->leftJoin('q_b_analytics', function ($join) use ($userId) {
                $join->on('question_banks.id', '=', 'q_b_analytics.qb_id')
                    ->where('q_b_analytics.user_id', '=', $userId);
            })
            ->select('question_banks.*', DB::raw('NULL as answer'))
            ->whereNull('q_b_analytics.user_id')
            ->whereIn('question_banks.module_id', $module)
            ->pluck('question_banks.id')
            ->toArray();

        $incorrectlyAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->whereIn('question_banks.module_id', $module)
            ->where('q_b_analytics.is_answer', '=', 'false')
        // ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.id')
            ->toArray();

        $attemptedAnsweredQuestions = DB::table('question_banks')
            ->join('q_b_analytics', 'question_banks.id', '=', 'q_b_analytics.qb_id')
            ->select('question_banks.*', 'q_b_analytics.is_answer')
            ->where('q_b_analytics.user_id', '=', $userId)
            ->whereIn('question_banks.module_id', $module)
        // ->groupBy('question_banks.subject_id')
            ->pluck('question_banks.id')
            ->toArray();

        $queryConditions = [];
        foreach ($questionType as $condition) {
            switch ($condition) {
                case 'attempted':
                    $queryConditions[] = $attemptedAnsweredQuestions;
                    break;
                case 'unattempted':
                    $queryConditions[] = $unattemptedQuestions;
                    break;
                case 'incorrect':
                    $queryConditions[] = $incorrectlyAnsweredQuestions;
                    break;
                default:
                    break;
            }
        }
        return $queryConditions;

    }

    //result
    public static function result($topicId, $userId)
    {

        $qustionAnalytics = DB::table('question_banks')
            ->leftJoin('q_b_analytics', function ($join) use ($userId, $topicId) {
                $join->on('question_banks.id', '=', 'q_b_analytics.qb_id')
                    ->where('q_b_analytics.user_id', '=', $userId);
            })

            ->select('question_banks.module_id', 'question_banks.topic_id', 'question_banks.subject_id', 'q_b_analytics.qb_id', 'q_b_analytics.is_unattempt_answer', 'q_b_analytics.is_answer', 'q_b_analytics.attempt_time')
            ->where('question_banks.topic_id', $topicId)
            ->get();
        if (count($qustionAnalytics) > 0) {
            $questionAttemptTime = [];
            foreach ($qustionAnalytics as $key => $value) {
                $questionAttemptTime[] = $value->attempt_time;
                $subjectName = Subject::where('id', $value->subject_id)->select('id', 'subject_name')->first();
                $topicId = $value->topic_id;
            }

            $subjectId = $subjectName->id;
            $subjectName = $subjectName->subject_name;

            $totalQuestion = $qustionAnalytics->count();
            $attemptedQuestion = $qustionAnalytics->where('qb_id', '!=', null)->where("is_unattempt_answer", 0)->count();

            $unattemptedQuestion = $qustionAnalytics->where('qb_id', '!=', null)->where("is_unattempt_answer", 1)->count();
            $incorrectAnswer = $qustionAnalytics->where('is_answer', 'false')->count();
            $correctAnswer = $qustionAnalytics->where("is_unattempt_answer", 0)->where('is_answer', 'true')->count();
            $accuracy = round($correctAnswer * 100 / $totalQuestion, 2) . "%";
/*
$times = $questionAttemptTime;
$seconds = 0;
foreach ($times as $time) {
if ($time !== null) {
$seconds += strtotime($time) - strtotime('today');
}
}
$totalTime = $seconds;
$accuracyPerSec = ($correctAnswer/ $totalTime) * 1000;
dd($accuracyPerSec);*/
            $times = $questionAttemptTime;
            //dd($times);
            $validTimes = array_filter($times, function ($time) {
                return $time !== null;
            });

            if (!empty($validTimes)) {
                $totalQuestion = count($validTimes);

                $totalTime = 0;
                foreach ($validTimes as $time) {
                    $timeParts = explode(':', $time);
                    $seconds = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];
                    $totalTime += $seconds;
                }

                $speedPerQuestion = $totalTime / $totalQuestion;
                $speedPerQuestion = round($speedPerQuestion, 2);
            }
            return $data = [
                'subjectId' => $subjectId,
                'topic_id' => $topicId,
                'subjectName' => $subjectName,
                'totalQuestion' => $totalQuestion,
                'attemptedQuestion' => $attemptedQuestion,
                'incorrectAnswer' => $incorrectAnswer,
                'correctAnswer' => $correctAnswer,
                'accuracy' => $accuracy,
                'speedPerQuestion' => @$speedPerQuestion,
                'unattemptedQuestion' => $unattemptedQuestion,
            ];
        } else {
            return $data = [];
        }
    }

    //send push notification
    public static function notify($message, $userId)
    {

        $user_data = User::where('fcm_token', '!=', null)->where('id', $userId)->where('push_notification', 1)->select('id', 'fcm_token')->get();
        //   dd($user_data);
        $all_tokens = array();
        $targetId = array();
        foreach ($user_data as $val) {

            if (!empty($val->fcm_token)) {
                array_push($all_tokens, $val->fcm_token);
                array_push($targetId, $val->id);
            }
        }
        if (count($all_tokens) > 0) {
            $token = $all_tokens;
        }
        $notification_data = array(
            "action_type" => 0,
            "action_id" => $targetId,
            "title" => "Auricle",
            "body" => $message,
        );
        if (count($all_tokens) > 0) {
            $result = FirebaseHelper::CustomNotification($notification_data, $token);
            return $result;
        } else {
            return "FCM is token is null.";
        }
    }
    public static function notifyScheduleClass($userRecords, $message)
    {
        $user_data = User::whereIn('id', $userRecords)->where('push_notification', 1)->select('id', 'fcm_token')->get();

        $all_tokens = array();
        $targetId = array();
        foreach ($user_data as $val) {

            if (!empty($val->fcm_token)) {
                array_push($all_tokens, $val->fcm_token);
                array_push($targetId, $val->id);
            }
        }
        if (count($all_tokens) > 0) {
            $token = $all_tokens;
        }
        // dd($token);
        $notification_data = array(
            "action_type" => 0,
            "action_id" => $targetId,
            "title" => "Auricle",
            "body" => $message,
        );
        if (count($all_tokens) > 0) {
            //dd($token);
            $result = FirebaseHelper::CustomNotification($notification_data, $token);
            //return $result;
        } else {
            return "Not have any fcm token";
        }

    }
}
