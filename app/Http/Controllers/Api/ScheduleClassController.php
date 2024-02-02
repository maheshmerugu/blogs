<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\VideoController;
use App\Models\Subject;
use App\Models\ScheduleClass;
use Carbon\Carbon;
use App\Helpers\Helper;
use DB;

class ScheduleClassController extends Controller
{
    //
    /**
    *  @OA\Get(
    *     path="/api/schedule-class/get-subjects",
    *     tags={"Schedule Classes"},
    *     summary="Schedule Classes",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="getSubjectForClasses",
    *  @OA\Parameter(
    *         name="year_id",
    *         in="query",
    *         description="Enter year id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
    *            
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="search",
    *         in="query",
    *         description="Search Subject",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="anatomy",
    *            
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function getSubjectForClasses(Request $request)
    {
        try {

            $data = Validator::make($request->all(), [
                'year_id' => 'required',
                'search' => 'nullable|string|max:25',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }


            $subjects = Subject::query()->leftJoin('schedule_classes', function ($join) {
                $join->on('subjects.id', '=', 'schedule_classes.subject_id');

            })
                ->join('teachers', 'subjects.id', '=', 'teachers.subject_id')
                ->select('subjects.id', 'teachers.id as teacher_id', 'subjects.year_id', 'subjects.subject_name', 'teachers.teacher_name', 'schedule_classes.date', 'schedule_classes.start_time', 'schedule_classes.end_time')
                ->where('subjects.year_id', $request->year_id)
                ->where('subject_name', 'LIKE', "%{$request->search}%")
                ->where('schedule_classes.date','!=',null)
                ->where('schedule_classes.start_time','!=',null)
                ->where('schedule_classes.end_time','!=',null)
                ->groupBy('subject_name')
                ->get();
                
            //      $ongoing = [];
            // $previous = [];
            foreach ($subjects as $key => $value) {
               
                $value->teacher_id = intval($value->teacher_id); 
     
                //  $startTime, $endTime, and $date are variables containing the dynamic values
                $startTime = $value->start_time;
                $endTime = $value->end_time;
                $date = $value->date;

                // convert start and end times to Carbon objects
                $carbonStartTime = Carbon::createFromFormat('H:i', $startTime);
                $carbonEndTime = Carbon::createFromFormat('H:i', $endTime);

                // combine date with start and end times
                $startDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonStartTime->hour, $carbonStartTime->minute, 0));
                $endDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonEndTime->hour, $carbonEndTime->minute, 0));

                $currentTime = strtotime(Carbon::now('Asia/Kolkata'));
                if ($currentTime > $startDateTime) {
                    // check if the current time is between the start and end times
                    if (($currentTime <= $endDateTime) && ($currentTime >= $startDateTime)) {

                
                        $value->is_live = 1;
                       // $ongoing[] = $value;
                    } else {
               

                        $value->is_live = 0;
                        //$previous[] = $value;

                    }
                }else{
                    $value->is_live = 0;
                }

            }

            /*
             * Start subscription check for video
             * Get subscription status of the user
             * 1=>suscribed,0=>un_suscribed
             */
            $subscriptionCheck = Helper::checkUserSubscriptionForVideo();
            $check = json_decode($subscriptionCheck);
            if ($check->code == 1 || $check->code == 2 || $check->code == 0) {

                $is_suscribed = 1;
            } else {

                $is_suscribed = 1;
            }
            $data = [

                'is_suscribed' => $is_suscribed,
                'subjects' => $subjects
            ];
            return res_success('Success', $data);

            // echo json_encode($subjects); exit;

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/ongoing-previous-events",
    *     tags={"Schedule Classes"},
    *     summary="Schedule Classes",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="ongoingPrevious",
    *  @OA\Parameter(
    *         name="subject_id",
    *         in="query",
    *         description="Enter subject id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="4",
    *            
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="search",
    *         in="query",
    *         description="Search Video Title",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="anatomy",
    *            
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function ongoingPrevious(Request $request)
    {
        try {

            $data = Validator::make($request->all(), [
                'subject_id' => 'required',
                'search' => 'nullable|string|max:25',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $awsUrl = env('AWS_URL');
            $event = ScheduleClass::select('schedule_classes.date', 'schedule_classes.start_time', 'schedule_classes.end_time', 'schedule_classes.email', 'schedule_classes.zoom_link', 'subjects.id as subject_id', 'subjects.subject_name', 'topics.topic as video_title', 'teachers.teacher_name','teachers.teacher_image')
                ->join('subjects', 'schedule_classes.subject_id', '=', 'subjects.id')
                ->join('topics', 'schedule_classes.topic_id', '=', 'topics.id')
                ->join('teachers', 'schedule_classes.teacher_id', '=', 'teachers.id')
                ->whereDate('date', '<=', Carbon::today()->format('Y-m-d'))
                ->where('schedule_classes.subject_id', $request->subject_id)
                ->where('topics.topic', 'LIKE', "%{$request->search}%")
                ->get();
           
            $ongoing = [];
            $previous = [];
            foreach ($event as $key => $value) {
                 if($value->teacher_image){
                         $value->teacher_image = asset('images/teacher/').'/'  . $value->teacher_image;
                    }else{
                        $value->teacher_image = "";
                    }
                //  $startTime, $endTime, and $date are variables containing the dynamic values
                $startTime = $value->start_time;
                $endTime = $value->end_time;
                $date = $value->date;

                // convert start and end times to Carbon objects
                $carbonStartTime = Carbon::createFromFormat('H:i', $startTime);
                $carbonEndTime = Carbon::createFromFormat('H:i', $endTime);

                // combine date with start and end times
                $startDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonStartTime->hour, $carbonStartTime->minute, 0));
                $endDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonEndTime->hour, $carbonEndTime->minute, 0));

                $currentTime = strtotime(Carbon::now('Asia/Kolkata'));
                if ($currentTime > $startDateTime) {
                    // check if the current time is between the start and end times
                    if (($currentTime <= $endDateTime) && ($currentTime >= $startDateTime)) {

                        $value->is_live = 1;
                        $ongoing[] = $value;
                    } else {

                        $value->is_live = 0;
                        $previous[] = $value;

                    }
                }

            }
            $data = [
                'ongoing' => $ongoing,
                'previous' => $previous
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Get(
    *     path="/api/date-filter-events",
    *     tags={"Schedule Classes"},
    *     summary="Schedule Classes",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="dateFilterEvents",
    *  @OA\Parameter(
    *         name="date",
    *         in="query",
    *         description="Enter date",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="2023-03-20",
    *            
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */

    public function dateFilterEvents(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'date' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
             $awsUrl = env('AWS_URL');
            $event = ScheduleClass::select('schedule_classes.date', 'schedule_classes.start_time', 'schedule_classes.end_time', 'schedule_classes.email', 'schedule_classes.zoom_link', 'subjects.subject_name', 'topics.topic as video_title', 'teachers.teacher_name','teachers.teacher_name')
                ->join('subjects', 'schedule_classes.subject_id', '=', 'subjects.id')
                ->join('topics', 'schedule_classes.topic_id', '=', 'topics.id')
                ->join('teachers', 'schedule_classes.teacher_id', '=', 'teachers.id')
                ->where('date', '=', $request->date)
                ->get();

            foreach ($event as $key => $value) {
                if($value->teacher_image){
                         $value->teacher_image = asset('images/teacher/').'/'  . $value->teacher_image;
                    }else{
                        $value->teacher_image = "";
                    }
                //  $startTime, $endTime, and $date are variables containing the dynamic values
                $startTime = $value->start_time;
                $endTime = $value->end_time;
                $date = $value->date;

                // convert start and end times to Carbon objects
                $carbonStartTime = Carbon::createFromFormat('H:i', $startTime);
                $carbonEndTime = Carbon::createFromFormat('H:i', $endTime);

                // combine date with start and end times
                $startDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonStartTime->hour, $carbonStartTime->minute, 0));
                $endDateTime = strtotime(Carbon::parse($date, Config::get('app.timezone'))->setTime($carbonEndTime->hour, $carbonEndTime->minute, 0));

                $currentTime = strtotime(Carbon::now('Asia/Kolkata'));
                if ($currentTime > $startDateTime) {
                    // check if the current time is between the start and end times
                    if (($currentTime <= $endDateTime) && ($currentTime >= $startDateTime)) {

                        $value->is_live = 1;

                    } else {

                        $value->is_live = 0;

                    }
                }

            }
            $data = [
                'events' => $event,
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
}