<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Notification;
use App\Helpers\FirebaseHelper;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;

class ReminderController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/set-reminder",
     *     tags={"Reminder"},
     *     summary="Set Reminder",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="setReminder",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Enter title",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *              default="reminder name",
     *           
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="datetime",
     *         in="query",
     *         description="Enter date",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="string", 
     *             default="12-19-2019 12:30:00",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="notify_me",
     *         in="query",
     *         description="Enter time",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *             default="1234",
     *            
     *                      
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * )
     */
    //set reminder
    public function setReminder(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'title' => 'required',
                'datetime' => 'required',
                'notify_me' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $createRemnder = Reminder::create([
                'user_id' => auth('api_user')->user()->id,
                'title' => $request->title,
                'datetime' => $request->datetime,
                'notify_me' => $request->notify_me,

            ]);
            return res_success('Success', $createRemnder);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Post(
     *     path="/api/edit-reminder",
     *     tags={"Reminder"},
     *     summary="Edit Reminder",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="editReminder",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="reminder_id",
     *         in="query",
     *         description="Enter reminder id which you want to update",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer",
     *              default="1",
     *           
     *            
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Enter title",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *              default="reminder name",
     *           
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="datetime",
     *         in="query",
     *         description="Enter date",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             type="string", 
     *             default="12-19-2019 12:30:00",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="notify_me",
     *         in="query",
     *         description="Enter time",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *             default="1234",
     *            
     *            
     *            
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * )
     */
    //edit reminder
    public function editReminder(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'reminder_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $checkReminder = Reminder::where(['user_id' => auth('api_user')->user()->id, 'id' => $request->reminder_id])->first();
            if ($checkReminder) {

                $update = Reminder::where(['user_id' => auth('api_user')->user()->id, 'id' => $request->reminder_id])->update([
                    'title' => $request->title,
                    'datetime' => $request->datetime,
                    'notify_me' => $request->notify_me
                ]);
                return res_success('Reminder updated successfully.');
            } else {
                return res_failed('Reminder id is not valid.');

            }
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Delete(
     *     path="/api/delete-reminder",
     *     tags={"Reminder"},
     *     summary="Delete Reminder",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="deleteReminder",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="reminder_id",
     *         in="query",
     *         description="Enter reminder id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer",
     *              default="1",
     *           
     *            
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * )
     */

    //delete reminder
    public function deleteReminder(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'reminder_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $reminder = Reminder::where('id', $request->reminder_id)->first();

            if ($reminder) {

                $reminder->delete();
                return res_success('Reminder deleted successfully.');
            } else {

                return res_failed('Reminder id is not valid.');
            }
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    public function sendNotification(Request $request)
    {

        $user_data = User::where('fcm_token', '!=', null)->select('id', 'fcm_token')->get();
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
            "body" => "Hii this is auricle"
        );
        FirebaseHelper::CustomNotification($notification_data, $token);
    }

     /**
    *  @OA\Get(
    *     path="/api/list-reminder",
    *     tags={"Reminder"},
    *     summary="List reminder",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="listReminder",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
     public function listReminder()
     {
         $user = auth('api_user')->user()->id;
         $reminder = Reminder::where('user_id',$user)->orderBy('id','desc')->get();
         foreach($reminder as $remind){
             $remind->notify_me = strval($remind->notify_me);
         }
         $data = ['reminders'=>$reminder];
         return res_success('Success', $data);
     }

     /**
    *  @OA\Post(
    *     path="/api/status-push-notification",
    *     tags={"Reminder"},
    *     summary="Update push notification status",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="statusPushNotification",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
     //Update push notification status for the specific user
     public function statusPushNotification(){
        try {
            $user = auth('api_user')->user();
            if($user->push_notification == 1){
                $user->push_notification = 0;
            }else{
                $user->push_notification = 1;
            }

            $user->save();
          
            return res_success('Success', $user);
        }catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
     }

       /**
    *  @OA\Get(
    *     path="/api/notification",
    *     tags={"Reminder"},
    *     summary="Notification list",
    *     description="Notification lists",
    *     security={{"bearerAuth":{}}},
    *     operationId="notification",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */

     public function notification()
     {
        try{
            $notification = DB::table('notifications')->where('user_id', auth('api_user')->user()->id)->orderBy('id','desc')->paginate(10);
            // return $notification; die;
            $data = [
                'notifications'=>$notification
            ];
            return res_success('Success',$data);
        }catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
     }
}