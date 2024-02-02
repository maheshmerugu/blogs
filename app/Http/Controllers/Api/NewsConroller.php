<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\McqQuestion;
use App\Models\McqAnalytic;
use App\Models\McqOption;
use App\Models\NewsViewCount;
use App\Models\VideoPlayStatus;
use App\Models\Bookmark;
use App\Models\Video;
use App\Models\StreakCount;
use App\Models\Subject;
use App\Models\ScheduleClass;
use App\Helpers\Helper;
use Exception;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\PlanSubscription as Plan;


class NewsConroller extends Controller
{

    /**
    *  @OA\Get(
    *     path="/api/home",
    *     tags={"Home Screen"},
    *     summary="Home Screen Data",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="home",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function home(Request $request)
    {

        try {
            
            $awsUrl = env('AWS_URL');
        //   return $awsUrl; die;    
           $user = auth('api_user')->user()->id;
            // return $user; die;
            if (isset($user)) {

                //App news data
                $data['appnews'] = News::select('id', 'title', 'description','news_image', 'type', 'views as news_count')->where('type', 1)->orderBy('id','desc')->limit(5)->get();
                
                foreach ($data['appnews'] as $key => $value) {
                    $value->news_count = intval($value->news_count); 
                    if($value->news_image){
                         $value->news_image = asset('images/news/appnews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    
                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                    if ($value->is_bookmark == null) {
                        $value->is_bookmark = 0;
                    }
                   
                }

                //Media news data
                $data['medianews'] = News::select('id', 'title', 'description', 'news_image', 'type', 'views as news_count')->withCount('bookmark as is_bookmark')->orderBy('id','desc')->where('type', 2)->limit(5)->get();
                
                foreach ($data['medianews'] as $key => $value) {
                    if($value->news_image){
                         $value->news_image = asset('images/news/medianews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    $value->news_count = intval($value->news_count);
                    
                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                    if ($value->is_bookmark == null) {
                        $value->is_bookmark = 0;
                    }
                    
                }
            
                //Mcq Question of the day
                $today = now()->toDateString();
                $yearId = auth('api_user')->user()->year_id;
                
                $McqQuestion = McqQuestion::where('status', 1)
                    ->where('year_id', $yearId)
                    ->whereDate('mcq_date', $today)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($McqQuestion) {
                  if($McqQuestion->mcq_explanation == null ){
                         $McqQuestion->mcq_explanation = "";
                     }
                }
        //   echo "<pre>"; print_r(auth('api_user')->user()->year_id); die;
                if (!empty($McqQuestion)) {
                    if($McqQuestion->question_image){
                         $McqQuestion->question_image = asset('public/images/mcq_image/question_image/').'/'  . $McqQuestion->question_image;
                    }
                   if( $McqQuestion->explanation_image){
                         $McqQuestion->explanation_image = asset('public/images/mcq_image/explanation_image/').'/'  . $McqQuestion->explanation_image;
                   }
                   
                    //Mcq options list
                    $options = McqOption::where('mcq_id', $McqQuestion->id)->get();

                    //How many users attempt mcq count
                    $total_user_answer_count = McqAnalytic::where('mcq_id', $McqQuestion->id)->count();

                    $optionArray = [];
                    if ($options->count() > 0) {
                        foreach ($options as $option) {
                            //Count mcq options
                            $totla_option_count = McqAnalytic::where(['mcq_id' => $McqQuestion->id, 'user_answer' => $option->id])->count();

                            //Check which option choose by user
                            $check_selected_option = McqAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();

                            //Calculate percentage of options
                            if ($totla_option_count > 0) {
                                $poll_percent = ($totla_option_count / $total_user_answer_count) * 100;
                            } else {
                                $poll_percent = 0;
                            }
                             $option_image_url = ''; 

                        if (isset($option->option_image) && $option->option_image) {
                            
                            $option_image_url =  asset('public/images/mcq_image/options/').'/'  . $option->option_image;
                        }
                            $optionArray[] = [
                                'option_id' => $option->id,
                                'title' => $option->option,
                                 'option_image' => $option_image_url,
                                'poll_percent' => round($poll_percent),
                                //'total_post_count' => count($options),
                                // 'option_count' => $totla_option_count,
                                'select_status' => ($check_selected_option) ? 1 : 0,
                            ];
                        }

                        //Check which option choose by user
                        $check_selected_option = McqAnalytic::where(['user_id' => $user, 'mcq_id' => $McqQuestion->id])->first();

                        //if user attempted =>1, un-attempted=>0
                        if ($check_selected_option) {
                            $McqQuestion->is_attempt = 1;
                            $McqQuestion->user_answer = $check_selected_option->user_answer;
                        } else {
                            $McqQuestion->is_attempt = 0;
                        }


                    }

                }
                //Mcq Question
                $data['mcq_question'] = $McqQuestion;
                if (!empty($optionArray)) {
                    $data['mcq_question']->option = $optionArray;
                }


              
                //Get mcq-id from users answer table(mcq_analytics)
                $mcqId      = McqAnalytic::where('user_id', auth('api_user')->user()->id)
                    ->pluck('mcq_id');

                //Get where user not attempt the daily mcq
                $mcqAnalytic = McqQuestion::select('mcq_questions.*')
                    ->whereNotIn('id', $mcqId)
                   
                    ->where('year_id',auth('api_user')->user()->year_id)
                    ->whereDate('created_at', '<', now()->toDateString())
                    ->get();
              
                if ($mcqAnalytic->count()) {
                    $data['missed_mcq_status'] = 1; //1=>user missed the mcq
                } else {
                    $data['missed_mcq_status'] = 0; //1=>user not missed the mcq

                }
                /*
                 * Start subscription check for video
                 * Get subscription status of the user
                 * 1=>suscribed,0=>un_suscribed
                 */
                  
                $subscriptionCheck = Helper::checkUserSubscriptionForVideo();
              
                $check = json_decode($subscriptionCheck);
                 if ($check->code == 1 || $check->code == 2|| $check->code == 0) {
                $data['is_suscribeds'] = 0;
                 }
                // if ($check->code == 1 || $check->code == 2|| $check->code == 0) {
                //      $data['get_countinue_video'] = [];
                //     $data['is_suscribed'] = 0;
                // } else {
                      //Countinue watch videos
                $latestSubscription = Subscription::where('user_id', $user)
                                    ->orderBy('id', 'desc')
                                    ->first();
                $countinueVideos = VideoPlayStatus::where('user_id', $user)->where('status',1)->pluck('video_id')->toArray();
                $data['get_countinue_video'] = Video::select('videos.*',DB::raw("CONCAT('" . asset('public/storage/app/documents/').'/' . "', videos.doc_url) AS doc_url"),DB::raw("CONCAT('" . asset('public/video/thumbnail/') .'/' . "', videos.video_thumbnail) AS video_thumbnail"),  'teachers.teacher_name','video_play_statuses.watch_time','video_play_statuses.is_progress_video')
                    ->join('teachers', 'videos.teacher_id', '=', 'teachers.id')
                    ->join('topics', 'videos.topic_id', '=', 'topics.id')
                    ->join('video_play_statuses', 'videos.id', '=', 'video_play_statuses.video_id')
                    ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
                    ->whereIn('videos.id', $countinueVideos)
                    ->where('video_play_statuses.user_id',$user)
                    // ->where('video_play_statuses.subcription_id')
                    ->orderBy('video_play_statuses.id','desc')
                    ->groupBy('videos.id')
                    ->limit(5)
                    ->get();
                     foreach ($data['get_countinue_video'] as $key => $value) {
                       $value->subject_name = Subject::where('id',$value->subject_id)->pluck('subject_name')->first();
                    }
                    $data['is_suscribed'] = 1;
                    
                //}
                //check user has question bank access or not
                $checkAccessToQb = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

                if ($checkAccessToQb) {
                    $plan_check = Plan::where('id', $checkAccessToQb->plan_id)->first();
                    if ($plan_check && $plan_check->access_to_question_bank == 1) {
                        $data['is_access_qb'] = 1;
                    } else {
                        $data['is_access_qb'] = 1;
                    }
                    // Check if the subscription has expired
                    if (Carbon::now()->greaterThan($checkAccessToQb->expiry_date)) {
                        // Handle the case where the subscription has expired
                        // You might want to update the user's access or notify them
                        $data['is_access_qb'] = 1; // Assuming access is revoked after expiration
                    }
                } else {
                    $data['is_access_qb'] = 1;
                }
                //end subscription for video

                //Get subscription data of the user
                 $subscription       = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

                //Check plan which plan purchase by the user
                $plan_check          = Plan::where('id', @$subscription->plan_id)->first();

                //Get how much time user watch the video (in second).
                @$video_used_seconds = VideoPlayStatus::where(['subcription_id' => $subscription->id, 'user_id' => auth('api_user')->user()->id])->pluck('watch_time')->sum();
              
                
                //Convert second to hour
                $seconds = $video_used_seconds;
                $hours   = $seconds / 3600;
                 // $hours = number_format((float)$hours, 5, '.', '');

                 $data['total_watch_hours'] = $seconds;
                //Streak Count 
                $data['streak_counts']      = StreakCount::where('user_id', $user)->count();
                if(auth('api_user')->user()->profile_img){
                         $userProfileImage = asset('images/user_image/').'/'  . auth('api_user')->user()->profile_img;
                    }else{
                        $userProfileImage= "";
                    }
                $data['profile_img']        = $userProfileImage;

                 @$data['plan_name']        = $plan_check->plan_name;
                
                  $data['plan_watch_hours'] = $plan_check->watch_hours ?? 0;


                  /*
                * Upcoming videos start
                */
                $awsUrl = env('AWS_URL');
                $data['upcomingVideos'] = ScheduleClass::select('schedule_classes.year_id as year','schedule_classes.date', 'schedule_classes.start_time', 'schedule_classes.end_time', 'schedule_classes.email', 'schedule_classes.zoom_link', 'subjects.id as subject_id', 'subjects.subject_name', 'topics.topic as video_title', 'teachers.teacher_name','teachers.teacher_image')
                ->join('subjects', 'schedule_classes.subject_id', '=', 'subjects.id')
                ->join('topics', 'schedule_classes.topic_id', '=', 'topics.id')
                ->join('teachers', 'schedule_classes.teacher_id', '=', 'teachers.id')
                // ->where('schedule_classes.year_id',auth('api_user')
                // ->user()->year_id)
                ->where('schedule_classes.date','>=',date('Y-m-d'))
                ->where('schedule_classes.start_time','>',date('H:i:s'))    
                ->orderBy('schedule_classes.id','desc')
                // ->limit(4)
                ->get();
                foreach($data['upcomingVideos'] as $value){
                     if($value->teacher_image){
                         $value->teacher_image = asset('images/teacher/').'/'  . $value->teacher_image;
                    }else{
                        $value->teacher_image = "";
                    }
                   $value->year = intval($value->year); 
                }
                 /*
                * Upcoming videos end
                */
                return res_success('Success', $data);
            } else {
                return res_failed('Failed!');
            }
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/news-list",
    *     tags={"News"},
    *     summary="All News",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="newsList",
    *     @OA\Parameter(
    *          name="type",
    *          required=true,
    *          description="array of type numbers",
    *          in="query",     
    *          @OA\Schema( 
    *              type="array", 
    *              @OA\Items(type="enum", enum={1,2}),
    *              
    *          )
    *      ),
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
    public function newsList(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'type' => 'required',
                'search' => 'nullable|string|max:25',
            ]);

            if ($validation->fails()) {

                return res_failed('Type is required.');
            }
             $awsUrl = env('AWS_URL');
            $user = auth('api_user')->user()->id;
           
            if ($request->type == 1) {
                $data['appnews'] = News::where('title', 'LIKE', "%{$request->search}%")->select('id','title','description','type','news_image','status', 'views as news_count', 'created_at','updated_at')->withCount('news as is_news_view')->where('type', '=', 1)->orderBy('id','desc')->get();
                 // return $data['appnews']; die;
                foreach ($data['appnews'] as $key => $value) {
                    // $value->news_count = intval($value->news_count); 
                     if($value->news_image){
                         $value->news_image = asset('images/news/appnews/').'/'  . $value->news_image;
                    }else{
                        $value->news_image = "";
                    }
                      $newsView = DB::table('news_view')->where(['user_id' => $user, 'news_id' => $value->id])->first();

                        if ($newsView !== null && property_exists($newsView, 'id')) {
                            // The property 'id' exists in the $newsView object
                            $value->is_news_view = 1;
                        } else {
                            // The $newsView is null or does not have the 'id' property
                            $value->is_news_view = 0;
                        }
                      $newsLike = DB::table('news_like')->where(['user_id' => $user, 'news_id' => $value->id])->pluck('isLiked')->first();

                        $value->isLiked = ($newsLike !== null) ? $newsLike : 0;

                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                    if ($value->is_bookmark == null) {
                        $value->is_bookmark = 0;
                    }
                   /* $userNewsViewCount = NewsViewCount::where(['user_id' => $user, 'news_id' => $value->id])->count();
                    echo $userNewsViewCount; die;
                    if (count($userNewsViewCount) > 0) {
                        $value->is_news_view = $userNewsViewCount;
                    } else {

                        $value->is_news_view = 0;
                    }*/
                }
                return res_success('Success', $data);
            } elseif ($request->type == 2) {
                $data['appnews'] = News::where('title', 'LIKE', "%{$request->search}%")->select('id','title','description','type','news_image','status', 'views as news_count','created_at','updated_at')->withCount('news as is_news_view')->where('type', '=', 2)->orderBy('id','desc')->get();
                foreach ($data['appnews'] as $key => $value) {
                    if($value->news_image){
                         $value->news_image = asset('images/news/medianews/').'/'  . $value->news_image;
                    }else{
                        $value->news_image = "";
                    }
                     $value->news_count = intval($value->news_count); 
                      $newsView = DB::table('news_view')->where(['user_id' => $user, 'news_id' => $value->id])->first();

                        if ($newsView !== null && property_exists($newsView, 'id')) {
                            // The property 'id' exists in the $newsView object
                            $value->is_news_view = 1;
                        } else {
                            // The $newsView is null or does not have the 'id' property
                            $value->is_news_view = 0;
                        }
                        $newsLike = DB::table('news_like')->where(['user_id' => $user, 'news_id' => $value->id])->pluck('isLiked')->first();
                        $value->isLiked = $newsLike;
                        $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                        if ($value->is_bookmark == null) {
                            $value->is_bookmark = 0;
                        }
                  /*  $userNewsViewCount = NewsViewCount::where(['user_id' => $user, 'news_id' => $value->id])->count();
                    // dd($value->is_news_view);
                    if (count($userNewsViewCount) > 0) {
                        $value->is_news_view = $userNewsViewCount;
                    } else {
                        $value->is_news_view = 0;
                    }*/
                }
                return res_success('Success', $data);
            } else {
                return res_failed('Enter valid type.');
            }

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Post(
    *     path="/api/news-view",
    *     tags={"News"},
    *     summary="View News",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="newsView",
    * @OA\Parameter(
    *         name="news_id",
    *         in="query",
    *         description="Enter news id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
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
    public function newsView(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'news_id' => 'required',
            ]);

            if ($validation->fails()) {

                return res_failed('News id is required.');
            }

            $news = NewsViewCount::create([
                'user_id' => auth('api_user')->user()->id,
                'news_id' => $request->news_id
            ]);
            return res_success('Success', $news);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/news-bookmark",
    *     tags={"News"},
    *     summary="Bookmark News",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="newsBookmark",
    * @OA\Parameter(
    *         name="news_id",
    *         in="query",
    *         description="Enter news id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
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
 


      public function newsBookmark(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'news_id' => 'required',
            ]);

            if ($validation->fails()) {
                return res_failed('News id is required.');
            }
            $usercheck = Bookmark::where(['news_id' => $request->news_id, 'user_id' => auth('api_user')->user()->id])->first();
            //dd($usercheck);
            if (!$usercheck) {
                $news = Bookmark::create([
                    'user_id' => auth('api_user')->user()->id,
                    'is_selected' => 1,
                    'news_id' => $request->news_id
                ]);
            } else {
                $news = Bookmark::where(['news_id' => $request->news_id, 'user_id' => auth('api_user')->user()->id])->update(['is_selected' => !$usercheck->is_selected]);
            }
            return res_success('Success', []);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    
    
     
     public function newslikemark(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'news_id' => 'required',
                'isLiked' => 'required',
            ]);
            if ($validation->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
           
            $get_news = DB::table('news_like')->where(['news_id' => $request->news_id, 'user_id' => auth('api_user')->user()->id])->pluck('news_id')->first();
            if(!empty($get_news)){
                $news_id = $request->news_id;
                $isLiked = $request->isLiked;
                $news = DB::table('news_like')->where(['news_id' => $news_id, 'user_id' => auth('api_user')->user()->id])->update(['isLiked' => $isLiked, 'updated_at' => date('Y-m-d H:i:s')]);
            }else{
                $news = DB::table('news_like')->insert(['news_id' => $request->news_id, 'isLiked' => $request->isLiked, 'user_id' => auth('api_user')->user()->id, 'created_at' => date('Y-m-d H:i:s')]);
            }
            return res_success('Success', $news);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

}