<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bookmark;
use App\Models\NewsViewCount;
use App\Models\News;
use App\Models\VideoBookmark;
use App\Models\StreakCount;
use App\Models\HandWrittingNotes;
use App\Models\BookmarkQuestionBank;
use App\Models\QuestionBank;
use App\Models\Subject;
use App\Models\Video;
use App\Models\VideoPlayStatus;
use App\Models\QuestionBankOption;
use App\Models\QBAnalytic;
use App\Models\Subscription;
use App\Models\PlanSubscription as Plan;

use App\Models\Module;
use App\Models\Topic;
use Carbon\Carbon;
use DB;

class BookmarkController extends Controller
{
    /**
     *  @OA\Get(
     *     path="/api/bookmarks-datas-list",
     *     tags={"Bookmark data on profile page"},
     *     summary="Bookmark List",
     *     description="1=> News, 2=>Video,3=>Question bank",
     *     security={{"bearerAuth":{}}},
     *     operationId="bookmarkDatas",
     *  @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Enter type",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer",
     *             default="1",
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search ",
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
    public function bookmarkDatas(Request $request)
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
            //check user has question bank access or not
           
            $checkAccessToQb = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();
            //dd(auth('api_user')->user()->id);
            if ($request->type == 1) {
                //Get bookmark news in array
                $bookmark = Bookmark::where(['is_selected' => 1, 'user_id' => $user])->orderBy('updated_at','desc')->pluck('news_id')->toArray();

                //App news data
                $data['appnews'] = News::select('news.*', 'news_image', 'type')->where('title', 'LIKE', "%{$request->search}%")->withCount('news')->whereIn('id', $bookmark)->where('type', 1)->orderBy('id','desc')->get();

                foreach ($data['appnews'] as $key => $value) {
                    $value->news_count = intval( $value->news_count);
                    if($value->news_image){
                         $value->news_image = asset('images/news/appnews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user, 'is_selected' => 1])->pluck('is_selected')->first();
                }

                //Media news data
                $data['medianews'] = News::select('news.*', 'news_image', 'type')->withCount('news')->withCount('bookmark as is_bookmark')->whereIn('id', $bookmark)->where('type', 2)->where('title', 'LIKE', "%{$request->search}%")->orderBy('id','desc')->get();
                foreach ($data['medianews'] as $key => $value) {
                     $value->news_count = intval( $value->news_count);
                      if($value->news_image){
                         $value->news_image = asset('images/news/medianews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user, 'is_selected' => 1])->pluck('is_selected')->first();
                }
            } elseif ($request->type == 2) {
              
                     if ($checkAccessToQb && $checkAccessToQb->expiry_date > now()) {
                    $plan_check = Plan::where('id', $checkAccessToQb->plan_id)->first();
                  
                     if ($plan_check && $plan_check->access_to_video == 1 ) {
                          //bookmark video list id in array
                        $bookmarkVideos = VideoBookmark::where('user_id', $user)->where('is_selected', 1)->pluck('video_id')->toArray();
                        //Get video list in the video list
                        $data['bookmark_videos'] = Video::whereIn('videos.id', $bookmarkVideos)->select('videos.*', 'teachers.teacher_name')
                         //   ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
                            ->join('topics', 'videos.topic_id', '=', 'topics.id')
                            ->join('teachers', 'videos.teacher_id', '=', 'teachers.id')
                            ->groupBy('videos.id')
                            ->where('topics.topic', 'LIKE', "%{$request->search}%")
                            ->get();


                foreach ($data['bookmark_videos'] as $key => $value) {

                    $value->is_bookmark = VideoBookmark::where(['is_selected' => 1, 'user_id' => $user])->where(['video_id' => $value->id])->pluck('is_selected')->first();
                    $value->is_bookmark = intval( $value->is_bookmark);
                     $value->subject_name = Subject::where('id',$value->subject_id)->pluck('subject_name')->first();
                }
                     }else{
                        
                         $data['bookmark_videos'] = [];
                     }
             }else{
                        
                         $data['bookmark_videos'] = [];
                     }
               
                 
               

            } elseif ($request->type == 3) {
                   if ($checkAccessToQb && $checkAccessToQb->expiry_date > now()) {
                    $plan_check = Plan::where('id', $checkAccessToQb->plan_id)->first();
                     if ($plan_check && $plan_check->access_to_question_bank == 1) {
                         
                     
                $questonBank = BookmarkQuestionBank::where('user_id',$user)->where('is_selected',1)->pluck('qb_id')->toArray();
                $getSubjectId = QuestionBank::whereIn('id', $questonBank)->groupBy('subject_id')->pluck('subject_id')->toArray();

                $data['bookmark_question_banks'] = Subject::whereIn('id', $getSubjectId)->select('id', 'subject_name')->where('subject_name', 'LIKE', "%{$request->search}%")->get();

                
                foreach ($data['bookmark_question_banks'] as $key => $value) {
                    // code...
                    $value->question_count = QuestionBank::whereIn('id', $questonBank)->where('subject_id',$value->id)->count();
                    }
                     }else{
                          $data['bookmark_question_banks'] = [];
                     }
                    
                  }else{
                      $data['bookmark_question_banks'] = [];
                  }
              
                /*$subjectCount = QuestionBank::whereIn('topic_id', $request->topic)->where('difficulty_level_id', $level)->select('subject_id')->groupBy('subject_id')->get();*/


            }
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    
    
    
    
    public function bookmarkDatasnew(Request $request)
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
            //check user has question bank access or not
           
            $checkAccessToQb = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();
            //dd(auth('api_user')->user()->id);
            if ($request->type == 1) {
                //Get bookmark news in array
                $bookmark = Bookmark::where(['is_selected' => 1, 'user_id' => $user])->orderBy('updated_at','desc')->pluck('news_id')->toArray();
              
                $data['appnews'] = News::select('news.*', 'news_image', 'type')->where('title', 'LIKE', "%{$request->search}%")->withCount('news')->whereIn('news.id',$bookmark)->where('type', 1)->orderBy('id','desc')->get();
                foreach ($data['appnews'] as $key => $value) {
                    //$value->news_count = intval( $value->news_count);
                    if($value->news_image){
                         $value->news_image = asset('images/news/appnews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    $new_is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user, 'is_selected' => 1])->pluck('is_selected')->first();
                    if($new_is_bookmark == null){
                       $value->is_bookmark = 0;
                    }else{
                        $value->is_bookmark =$new_is_bookmark;
                    }
                }

                //Media news data
                $data['medianews'] = News::select('news.*', 'news_image', 'type')->withCount('news')->withCount('bookmark as is_bookmark')->whereIn('id', $bookmark)->where('type', 2)->where('title', 'LIKE', "%{$request->search}%")->orderBy('id','desc')->get();
                foreach ($data['medianews'] as $key => $value) {
                     $value->news_count = intval( $value->news_count);
                      if($value->news_image){
                         $value->news_image = asset('images/news/medianews/').'/'  . $value->news_image;
                    }else{
                         $value->news_image = "";
                    }
                    $value->is_bookmark = Bookmark::where(['news_id' => $value->id, 'user_id' => $user, 'is_selected' => 1])->pluck('is_selected')->first();
                }
            } elseif ($request->type == 2) {
              
                    
                  
                          //bookmark video list id in array
                        $bookmarkVideos = VideoBookmark::where('user_id', $user)->where('is_selected', 1)->pluck('video_id')->toArray();
                        //Get video list in the video list
                        $data['bookmark_videos'] = Video::whereIn('videos.id', $bookmarkVideos)->select('videos.*', 'teachers.teacher_name')
                         //   ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
                            ->join('topics', 'videos.topic_id', '=', 'topics.id')
                            ->join('teachers', 'videos.teacher_id', '=', 'teachers.id')
                            ->groupBy('videos.id')
                            ->where('topics.topic', 'LIKE', "%{$request->search}%")
                            ->get();


                foreach ($data['bookmark_videos'] as $key => $value) {

                    $value->is_bookmark = VideoBookmark::where(['is_selected' => 1, 'user_id' => $user])->where(['video_id' => $value->id])->pluck('is_selected')->first();
                    $value->is_bookmark = intval( $value->is_bookmark);
                     $value->subject_name = Subject::where('id',$value->subject_id)->pluck('subject_name')->first();
                }
                  
      


            } elseif ($request->type == 3) {
                  
                $questonBank = BookmarkQuestionBank::where('user_id',$user)->where('is_selected',1)->pluck('qb_id')->toArray();
                $getSubjectId = QuestionBank::whereIn('id', $questonBank)->groupBy('subject_id')->pluck('subject_id')->toArray();

                $data['bookmark_question_banks'] = Subject::whereIn('id', $getSubjectId)->select('id', 'subject_name')->where('subject_name', 'LIKE', "%{$request->search}%")->get();

                
                foreach ($data['bookmark_question_banks'] as $key => $value) {
                    // code...
                    $value->question_count = QuestionBank::whereIn('id', $questonBank)->where('subject_id',$value->id)->count();
                    }
                    
              
                /*$subjectCount = QuestionBank::whereIn('topic_id', $request->topic)->where('difficulty_level_id', $level)->select('subject_id')->groupBy('subject_id')->get();*/


            }
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/get-qb-bookmark-module",
     *     tags={"Bookmark data on profile page"},
     *     summary="Bookmark List",
     *     security={{"bearerAuth":{}}},
     *     operationId="getQBBookmarkModule",
     *  @OA\Parameter(
     *         name="subject_id",
     *         in="query",
     *         description="Enter subject id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer",
     *             default="1",
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search ",
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
    public function getQBBookmarkModule(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'subject_id' => 'required|min:0',
                'search' => 'nullable|string|max:25',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
           /* $data= DB::table('bookmark_question_banks')->select('bookmark_question_banks.*','question_banks.id','question_banks.subject_id','question_banks.module_id','modules.module')
            ->join('question_banks','bookmark_question_banks.qb_id','=','question_banks.id')
            ->join('modules','question_banks.module_id','=','modules.id')
            ->where('bookmark_question_banks.user_id',$user)
            ->where('question_banks.subject_id',$request->subject_id)
            ->get();

            return $data->count(); die;
            foreach($module as $value){
              $data['count'] = $value->count();
            }*/
         //  $data = $module->groupBy('module_id')->get();
            //Get module id's subject wise
            $selecSubject = QuestionBank::where('subject_id', $request->subject_id)->groupBy('module_id')->pluck('module_id')->toArray();

            $userID = auth('api_user')->user()->id;

            //Get module name and question count
            $module = Module::select('id', 'module')
                    ->whereIn('id', $selecSubject)  
                    ->where('module', 'LIKE', "%{$request->search}%")
                    ->get();  
         
           foreach ($module as $key => $value) {
         
               $data = DB::table('bookmark_question_banks')->select('bookmark_question_banks.*','question_banks.module_id')
               ->join('question_banks','bookmark_question_banks.qb_id','=','question_banks.id')
               ->join('modules','question_banks.module_id','=','modules.id')
               ->where('question_banks.module_id',$value->id)
               ->where('bookmark_question_banks.user_id',$user)
               ->where('bookmark_question_banks.is_selected',1)
               ->get();
            
           

           $value->question_bank_count = count($data);
             if(count($data) <= 0){

            unset($module[$key]);

             
           }

           }
 
            return res_success('Success', $module);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/get-bookmark-question",
     *     tags={"Bookmark data on profile page"},
     *     summary="Bookmark List",
     *     security={{"bearerAuth":{}}},
     *     operationId="bookmarkQuestions",
     *  @OA\Parameter(
     *         name="module_id",
     *         in="query",
     *         description="Enter module id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer",
     *             default="1",
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search ",
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
    public function bookmarkQuestions(Request $request)
    {
        try {
             $validation = Validator::make($request->all(), [
                'module_id' => 'required',
                'search' => 'nullable',
            ]);

            if ($validation->fails()) {

                return res_failed('Module id is required.');
            }
             $user = auth('api_user')->user()->id;
            //Get module id's subject wise
            $qbArray = QuestionBank::where('module_id', $request->module_id)->pluck('id')->toArray();


            //Get module wise question
             $bookmark_questions = BookmarkQuestionBank::select('id', 'qb_id')->where(['is_selected'=>1,'user_id'=>$user])->whereIn('qb_id', $qbArray)->get();

            foreach ($bookmark_questions as $value) {
                
                $bookmark_qb_id[] = $value->qb_id;
                $topic = [];
                $module = [];
                foreach ($value->QuestionBank as $key => $questions) {
                    $module[] = $questions->module_id;
                    $topic[] = QuestionBank::where('module_id', $questions->module_id)->groupBy('topic_id')->pluck('topic_id')->toArray();
                }

            }

            // dd($bookmark_qb_id);
           if(@$topic != null){

                //Topic wise bookmarks questions
             $data['questions'] = Topic::select('id', 'topic')
                ->whereIn('id', $topic[0])->with([
                    'questionBank' => function ($query) use ($module, $request,$bookmark_qb_id) {
                        $query->where('module_id', $module)->whereIn('id',$bookmark_qb_id)->where('qb_question', 'LIKE', "%{$request->search}%");
                    }
                ])
                ->WhereHas('questionBank', function ($query) use ($module, $request,$bookmark_qb_id) {
                    $query->where('module_id', $module)->whereIn('id',$bookmark_qb_id)->where('qb_question', 'LIKE', "%{$request->search}%");
                })
                ->get();
                 //is bookmark
                 foreach ($data['questions'] as $key => $value) {
                    foreach($value->questionBank as $qb){
                        $question_image_url = ''; 
                        if ($qb->question_image) {
                            $qb->question_image = asset('images/question_bank/question_image/' . $qb->question_image);
                        }
                        $explanation_image_url = ''; 
                        if ($qb->explanation_image) {
                    
                            $qb->explanation_image = asset('images/question_bank/explanation_image/' . $qb->explanation_image);
                        }
                        $reference_image_url = '';
                        if ($qb->refrence_image) {
                        //   echo "1234567"; die;
                            $qb->refrence_image = asset('images/question_bank/refrence/' . $qb->refrence_image);
                        }
                         //get question related options
                    $options = QuestionBankOption::where('qb_id', $qb->id)->get();
                     $total_user_answer_count = QBAnalytic::where('qb_id', $qb->id)->count();
                    $optionsk = [];
                    if ($options->count() > 0) {
                        foreach ($options as $k => $option) {

                            $totla_option_count = QBAnalytic::where(['qb_id' => $qb->id, 'user_answer' => $option->id])->count();
                            $check_selected_option = QBAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();
                            if ($totla_option_count > 0) {
                                $poll_percent = ($totla_option_count / $total_user_answer_count) * 100;
                            } else {
                                $poll_percent = 0;
                            }
                             $option_image_url = ''; 

                        if (isset($option->option_image) && $option->option_image) {
                            $option_image_url =  asset('public/images/question_bank/option_image/').'/'  . $option->option_image;
                        }
                            $optionsk[] = [

                                'option_id' => $option->id,
                                'title' => $option->option,
                                 'option_image' => $option_image_url,
                                'poll_percent' => round($poll_percent),
                                //'total_post_count' => count($options),
                                // 'option_count' => $totla_option_count,
                                'select_status' => ($check_selected_option) ? 1 : 0,
                            ];
                        }



                    }
                    //check user that mcq_question attempted or not
                    $check_selected_option = QBAnalytic::where(['user_id' => $user, 'qb_id' => $qb->id])->first();
                    if ($check_selected_option) {
                        $qb->is_attempt = 1;
                        $qb->user_answer = $check_selected_option->user_answer;
                    } else {
                        $qb->is_attempt = 0;
                        $qb->user_answer = null;
                    }
                    $qb->option = $optionsk;
                    $qb->is_bookmark = BookmarkQuestionBank::where(['is_selected' => 1, 'user_id' => $user])->where(['qb_id' => $qb->id])->pluck('is_selected')->first();
                    }
                }
           }else{
            $data = [];
           }
           
             
            


               
                   


               
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Get(
    *     path="/api/notes-bookmarks-list",
    *     tags={"Bookmark data on profile page"},
    *     summary="Bookmark list of notes",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="notesBookmarkList",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * @OA\Parameter(
    *         name="search",
    *         in="query",
    *         description="Search ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="anatomy",
    *            
    *         )
    *     ),
    * ),
    
    *   
    * )
    */
    //Get handwritting data of the video  
    public function notesBookmarkList(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'search' => 'nullable|string|max:25',
            ]);

            if ($validation->fails()) {

                return res_failed('Type is required.');
            }
            $user = auth('api_user')->user()->id;
            $hand_writting_notes = HandWrittingNotes::where(['user_id' => $user])
                ->select('hand_writting_notes.id', 'hand_writting_notes.video_id', 'hand_writting_notes.video_time', 'hand_writting_notes.user_id', 'hand_writting_notes.content', 'hand_writting_notes.created_at', 'hand_writting_notes.updated_at', 'videos.topic_id',  'videos.video_title as video_title')
                ->join('videos', 'hand_writting_notes.video_id', '=', 'videos.id')
                ->join('topics', 'videos.topic_id', '=', 'topics.id')
                ->whereNotNull('videos.video_title') 
                ->where(function ($query) use ($request) {
                    $query->where('hand_writting_notes.content', 'LIKE', "%{$request->search}%")
                        ->orWhere('videos.video_title', 'LIKE', "%{$request->search}%");
                })
                ->groupBy('hand_writting_notes.id')
                ->orderBy('hand_writting_notes.id','desc')
                ->get();
              //  dd($hand_writting_notes);
                foreach($hand_writting_notes as $value){
                    $value->topic_id = intval($value->topic_id);
                }
            return res_success('Success', $hand_writting_notes);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/streak-count",
     *     tags={"Home Screen"},
     *     summary="Streak Count",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="streakCount",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * 
     * )
     */
    //Streak count (user how many time open the app)
    public function streakCount(Request $request)
    {
        try {
            $user = auth('api_user')->user()->id;
            $currentDate = Carbon::now();
            $streakCheck = StreakCount::where('user_id', $user)->whereDate('created_at', now())->first();

            if (!$streakCheck) {

                $streakRate = StreakCount::create([
                    'user_id' => auth('api_user')->user()->id
                ]);
            }

            return res_success('Success', (Object) []);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }


     /**
    *  @OA\Get(
    *     path="/api/video-watch-hour-list",
    *     tags={"Bookmark data on profile page"},
    *     summary="Video watch hour list",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="videoWatchHourList",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function videoWatchHourList(Request $request)
    {
        $user = auth('api_user')->user()->id;
        $countinueVideos = VideoPlayStatus::where('user_id', $user)
                ->join("videos","video_play_statuses.video_id","=","videos.id")
                ->join("topics","videos.topic_id","=","topics.id")
                ->join("teachers","videos.teacher_id","=","teachers.id")
                ->select("video_play_statuses.id","video_play_statuses.watch_time","videos.video_total_time","topics.topic as video_title","teachers.teacher_name")
                  ->where(function ($query) use ($request) {
                    $query->where('topics.topic', 'LIKE', "%{$request->search}%");
                })
                ->get();
        $data = [
            'videos'=>$countinueVideos
        ];
         return res_success('Success', $data);
    }
}