<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Module;
use App\Models\CustomQuestionBank;
use App\Models\CustomQBAnalytic;
use App\Models\QuestionBank;
use App\Models\QBAnalytic;
use App\Models\QuestionType;
use App\Models\BookmarkQuestionBank;
use App\Models\QuestionBankOption;
use App\Models\CustomQuestionBankResult;
use App\Models\QuestionBankResult;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use DB;

class QuestionBankController extends Controller
{

    /**
    *  @OA\Get(
    *     path="/api/select-question-bank",
    *     tags={"Custom Question Bank"},
    *     summary="Select Custom QB",
    *     description="0=>User not attempted any question,1=>countinue,2=>User result",
    *     security={{"bearerAuth":{}}},
    *     operationId="selectQuestionBank",    
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
     public function selectQuestionBank(Request $request)
    {
        try{
              
            $user = auth('api_user')->user()->id;
            $result = CustomQuestionBankResult::where('user_id',$user)->first();
           /* if($result){
                $data= [
                    'is_countinue' =>2
                ];
                return res_success("Success",$data);
            }*/
            $unattemptedQuestions = DB::table('question_banks')
                    ->leftJoin('custom_q_b_analytics', function ($join) use ($user) {
                        $join->on('question_banks.id', '=', 'custom_q_b_analytics.qb_id')
                             ->where('custom_q_b_analytics.user_id', '=', $user);
                    })
                    ->select('question_banks.*', DB::raw('NULL as answer'))
                    ->whereNull('custom_q_b_analytics.user_id')
                    ->groupBy('question_banks.topic_id')
                    ->pluck('question_banks.topic_id')
                    ->count();
            $CustomQuestionBankAnalytic = CustomQBAnalytic::where('user_id',$user)->count();
            
           if($CustomQuestionBankAnalytic == 0){
                $data = [
                    'is_countinue' =>0 // not attempted any question
                ];
                return res_success("Success",$data);
            }elseif ($unattemptedQuestions ==  0) {
               
                 $data= [
                    'is_countinue' =>2 //result
                ];
                return res_success("Success",$data);
            }else{
                $data = [
                    'is_countinue' =>1 //countinue
                ];
                return res_success("Success",$data);
            }

        }catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/select-year-level-type",
    *     tags={"Custom Question Bank"},
    *     summary="Select year, difficulty level and select custom or original question bank",
    *     description="1=> Custom, 2=>Original",
    *     security={{"bearerAuth":{}}},
    *     operationId="selectYearLevelType",
    *   @OA\Parameter(
    *          name="question_bank_type",
    *          required=true,
    *          description="array of type numbers",
    *          in="query",     
    *          @OA\Schema( 
    *              type="array", 
    *              @OA\Items(type="enum", enum={1,2}),
    *              
    *          )
    *      ),
    *  @OA\Parameter(
    *         name="year",
    *         in="query",
    *         description="Enter year",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
    *            
    *         )
    *     ),
    *   @OA\Parameter(
    *         name="difficulty_level",
    *         in="query",
    *         description="Enter difficulty level ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
    *            
    *         )
    *     ),
    *    
    @OA\Parameter(
    *      name="question_type[]",
    *      in="query",
    *      description="('attempted','unattempted','incorrect')",
    *      required=false,
    *      @OA\Schema(
    *        type="array",
    *        @OA\Items(type="string")
    *      )
    *    ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    /*
     * Start Custom Question Bank
     */

    public function selectYearLevelType(Request $request)
    {
        try {
            if ($request->question_bank_type == 1) {

                $data = Validator::make($request->all(), [
                    'year' => 'required',
                    'difficulty_level' => 'nullable',
                    'question_bank_type' => 'required',
                    //1=>custom QB ,2=>Original QB
                    'question_type' => 'required', //1=>Correct ,2=>Incorrect,3=>unattemppted
                ]);
            } else {
                $data = Validator::make($request->all(), [
                    'year' => 'required',
                    'question_bank_type' => 'required',
                    //1=>custom QB ,2=>Original QB
                ]);

            }
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            //  $get_year_by_subjects_id = Topic::where('year_id',$request->year)->groupBy('subject_id')->pluck('subject_id')->toArray();
            $user = auth('api_user')->user()->id;

            if ($request->question_bank_type == 1) { //Custom question bank
            
                 //save the request questionType             

                $questionType  = QuestionType::where('user_id',$user)->delete();

                foreach ($request->question_type as $key => $value) {
                    $insertQuestionType = QuestionType::create([
                        'user_id'=>$user,
                        'question_type'=>$value

                ]);
                }
                //get question type  (attempted,unatempted,incorrect) wise subject id
                $getQuestionData = Helper::getQuestions($user, $request->question_type, $request->year, $request->difficulty_level);
                // return $getQuestionData; die;
                $get_year_by_subjects_id = array_merge(...$getQuestionData);


            } else {
                //Original question bank
                $get_year_by_subjects_id = QuestionBank::where(['year_id' => $request->year])->groupBy('subject_id')->pluck('subject_id')->toArray();

            }
            $get_subject_name = Subject::whereIn('id', $get_year_by_subjects_id)->select('id', 'subject_name')->get();

            return res_success('Success', $get_subject_name);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/select-subject",
    *     tags={"Custom Question Bank"},
    *     summary="Subject wise module list",
    *     description="Subject wise module list",
    *     security={{"bearerAuth":{}}},
    *     operationId="selecSubject",
    *    @OA\Parameter(
    *      name="subject_id[]",
    *      in="query",
    *      description="A list of things.",
    *      required=true,
    *      @OA\Schema(
    *        type="array",
    *        @OA\Items(type="integer")
    *      )
    *    ),
     @OA\Parameter(
    *      name="question_type[]",
    *      in="query",
    *      description="('attempted','unattempted','incorrect')",
    *      required=false,
    *      @OA\Schema(
    *        type="array",
    *        @OA\Items(type="string")
    *      )
    *    ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
     public function selecSubject(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'subject_id' => 'required|array|min:0',
                'question_type' => 'required', //1=>Correct ,2=>Incorrect,3=>unattemppted
            ]);
            if ($data->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            //get question type
            $question_type = QuestionType::where('user_id',$user)->pluck('question_type')->toArray();
            //get modules  (attempted,unatempted,incorrect) wise subject id
            $getQuestionData = Helper::getQuestions1($user, $question_type, $request->subject_id);
            //merge all modules
            $question_id = array_merge(...$getQuestionData);
            //Get modules array
             $moduleSel = QuestionBank::whereIn('id',$question_id)->groupBy('module_id')->pluck('module_id')->toArray();
            
            //Get module list subject wise
            $selecSubject = QuestionBank::whereIn('subject_id', $request->subject_id)->groupBy('subject_id')->pluck('subject_id')->toArray();
            
            $subjectWithmodule = Subject::whereIn('id', $selecSubject)->select('id', 'subject_name')->with([
                'module' => function ($query) use ($request,$moduleSel) {
                    $query->whereIn('id', $moduleSel)->select('id', 'subject_id', 'module');
                }
            ])
            ->WhereHas(
                'module' , function ($query) use ($request,$moduleSel) {
                    $query->whereIn('id', $moduleSel)->select('id', 'subject_id', 'module');
                }
            )->get();
            return res_success('Success', $subjectWithmodule);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/select-module",
    *     tags={"Custom Question Bank"},
    *     summary="Module wise topic list",
    *     description="Subject wise module list",
    *     security={{"bearerAuth":{}}},
    *     operationId="selecModule",
    *    @OA\Parameter(
    *      name="module_id[]",
    *      in="query",
    *      description="A list of things.",
    *      required=true,
    *      @OA\Schema(
    *        type="array",
    *        @OA\Items(type="integer")
    *      )
    *    ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
  public function selecModule(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'module_id' => 'required|array|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $question_type = QuestionType::where('user_id',$user)->pluck('question_type')->toArray();
           
            //get modules  (attempted,unatempted,incorrect) wise subject id
            $getQuestionData = Helper::getQuestions2($user, $question_type, $request->module_id);
            //merge all modules
            $question_id = array_merge(...$getQuestionData);
            //Get modules array
            // dd($question_id);
             $topicSel = QuestionBank::whereIn('id',$question_id)->groupBy('topic_id')->pluck('topic_id')->toArray();
            //Get module list subject wise
            $selectModule = QuestionBank::whereIn('module_id', $request->module_id)->groupBy('module_id')->pluck('module_id')->toArray();
            $moduleWithtopics = Module::whereIn('id', $selectModule)
                                ->select('id', 'module')
                                ->with([
                                    'topics' => function ($query) use ($request, $topicSel, $question_id) {
                                        $query->whereIn('id', $topicSel)
                                            ->select('id', 'module_id', 'topic')
                                            ->withCount(['questionBank' => function ($query) use ($question_id) {
                                                $query->whereIn('id', $question_id);
                                            }]);
                                    }
                                ])
                                ->whereHas('topics', function ($query) use ($request, $topicSel) {
                                    $query->whereIn('id', $topicSel)
                                        ->select('id', 'module_id', 'topic')
                                        ->withCount('topics');
                                })

                                ->get();
                                foreach($moduleWithtopics as $value){
                                   
                                    foreach($value->topics as $val){
                                        $val->question_bank_count = intval($val->question_bank_count);
                                        
                                    }
                                }
            // dd($moduleWithtopics);

           /* //Get topic list module wise
            $selecModule = QuestionBank::whereIn('module_id', $request->module_id)->pluck('module_id')->toArray();

            $moduleWithtopics = Module::whereIn('id', $selecModule)->select('id', 'module')->with([
                'topics' => function ($query) use ($request) {
                    $query->whereIn('module_id', $request->module_id)->select('id', 'module_id', 'topic')->withCount('topics');

                }
            ])->get();*/

            return res_success('Success', $moduleWithtopics);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/create-custom-qb",
    *     tags={"Custom Question Bank"},
    *     summary="Create custom question bank",
    *     description="Create custom question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="createCustomQb",
    *    @OA\Parameter(
    *      name="topic[]",
    *      in="query",
    *      description="A list of things.",
    *      required=true,
    *      @OA\Schema(
    *        type="array",
    *        @OA\Items(type="integer")
    *      )
    *    ),
    * 
    *    @OA\Parameter(
    *         name="difficulty_level",
    *         in="query",
    *         description="Enter difficuty level",
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
    public function createCustomQb(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'topic' => 'required|array|min:0',
                'difficulty_level' => 'required',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $level = $request->difficulty_level;
            //Subject count
            $subjectCount = QuestionBank::whereIn('topic_id', $request->topic)->where('difficulty_level_id', $level)->select('subject_id')->groupBy('subject_id')->get();

            //module count
            $moduleCount = QuestionBank::whereIn('topic_id', $request->topic)->where('difficulty_level_id', $level)->select('module_id')->groupBy('module_id')->get();
            //topic count
            $topicCount = count(collect($request->topic));



            //get topics 
            $topics = QuestionBank::whereIn('topic_id', $request->topic)->where('difficulty_level_id', $level)->get();

            $user = auth('api_user')->user()->id;
            //question count
            $questionCount = $topics->count();

            // $deleteCustomQuestionBank = CustomQuestionBank::where('user_id', $user)->delete();
            $discardCustomQB = CustomQuestionBank::where('user_id', $user)->delete();
            $discardCustomQBAnalytic = CustomQBAnalytic::where('user_id', $user)->delete();
            $result = CustomQuestionBankResult::where('user_id', $user)->delete();
            //create custom question bank
            foreach ($topics as $value) {


                $createCustomQb = CustomQuestionBank::create([
                    'user_id' => $user,
                    'qb_id' => $value->id,
                    'year_id' => $value->year_id,
                    'subject_id' => $value->subject_id,
                    'module_id' => $value->module_id,
                    'topic_id' => $value->topic_id
                ]);



            }

            $data = [
                "question" => $questionCount,
                "subjects" => $subjectCount->count(),
                "module" => $moduleCount->count(),
                "topic" => $topicCount,
                "difficulty_level" => $level,
            ];

            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Get(
     *     path="/api/custom-questions-list",
     *     tags={"Custom Question Bank"},
     *     summary="Custom question bank list ",
     *     description="Custom question bank list",
     *     security={{"bearerAuth":{}}},
     *     operationId="customQuestionList",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * 
     * )
     */
    //get questions for custom question bank
    public function customQuestionList()
    {
        try {
            $user = auth('api_user')->user()->id;
            $customQuestionData = CustomQuestionBank::where('user_id', $user)->pluck('qb_id')->toArray();

            //Get where user not attempt the daily mcq
            $questionBank = QuestionBank::whereIn('id', $customQuestionData)->get();
        //   echo "<pre>"; echo $questionBank; die;
            
            if (!empty($questionBank)) {
                $questionArray = [];
                foreach ($questionBank as $key => $value) {
                    if($value->qb_explanation == null ){
                $value->qb_explanation = "";
            }
                     $question_image_url = ''; 
                        if ($value->question_image) {
                            $value->question_image = asset('public/images/question_bank/question_image/' . $value->question_image);
                        }
                        $explanation_image_url = ''; 
                        if ($value->explanation_image) {
                    
                            $value->explanation_image = asset('public/images/question_bank/explanation_image/' . $value->explanation_image);
                        }
                        $reference_image_url = '';
                        if ($value->refrence_image) {
                        //   echo "1234567"; die;
                            $value->refrence_image = asset('public/images/question_bank/refrence/' . $value->refrence_image);
                        }
                    $topicId = $value->topic_id;
                    //Bookmark
                    $value->is_bookmark = BookmarkQuestionBank::where(['qb_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                    if ($value->is_bookmark == null) {
                        $value->is_bookmark = 0;
                    }
                    //get question related options
                    $options = QuestionBankOption::where('qb_id', $value->id)->get();

                    $total_user_answer_count = CustomQBAnalytic::where('qb_id', $value->id)->count();
                     // Check if the option has an image
                        $option_image_url = ''; // Default value if no image is available

                      
                    $optionsk = [];
                    if ($options->count() > 0) {
                        foreach ($options as $k => $option) {

                            $totla_option_count = CustomQBAnalytic::where(['qb_id' => $value->id, 'user_answer' => $option->id])->count();
                            $check_selected_option = CustomQBAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();
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
                                'poll_percent' => round($poll_percent),
                                //'total_post_count' => count($options),
                                // 'option_count' => $totla_option_count,
                                'select_status' => ($check_selected_option) ? 1 : 0,
                                 'option_image' => $option_image_url, // Use the constructed URL or the default value

                            ];
                        }



                    }
                    //check user that mcq_question attempted or not
                    $check_selected_option = CustomQBAnalytic::where(['user_id' => $user, 'qb_id' => $value->id])->first();
                    if ($check_selected_option) {
                        $value->is_attempt = 1;
                        $value->user_answer = $check_selected_option->user_answer;
                    } else {
                        $value->is_attempt = 0;
                        $value->user_answer = null;
                    }
                    $value->option = $optionsk;
                    $questionArray[] = $value;
                }
            }
             $topicName = Topic::where('id',@$topicId)->pluck('topic')->first();

            $data=[
                'topic_name'=>@$topicName,
                'qb_question'=>$questionArray,
        ];

            return res_success('Success', $data);
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Post(
    *     path="/api/questions-attempt",
    *     tags={"Custom Question Bank"},
    *     summary="Attempt Custom  Test",
    *     description="Attempt custom question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="questionAttempt",
    *   @OA\Parameter(
    *         name="qb_id",
    *         in="query",
    *         description="Enter qb id ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *           
    *            
    *         )
    *     ),
    *       @OA\Parameter(
    *         name="option_id",
    *         in="query",
    *         description="Enter option ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *           
    *            
    *         )
    *     ),
    *   @OA\Parameter(
    *          name="qb_type",
    *          required=true,
    *          description="1=>Custom QB,2=>Original QB",
    *          in="query",     
    *          @OA\Schema( 
    *              type="array", 
    *              @OA\Items(type="enum", enum={1,2}),
    *              
    *          )
    *      ),
    *    @OA\Parameter(
    *          name="attempt_time",
    *          required=false,
    *          description="Question attemption time",
    *          in="query",     
    *          @OA\Schema( 
    *              type="string", 
    *              
    *              
    *          )
    *      ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    //attempt questions
        public function questionAttempt(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'qb_id' => 'required',
                'option_id' => 'required',
                'qb_type' => 'required',
                'attempt_time' => 'nullable',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            //Get daily mcq questions
            $QbQuestion = QuestionBank::where(['id' => $request->qb_id])->first();
            if ($request->qb_type == 1) { //1=>Custom QB,2=>Original QB
                //Get attempted users answer data
                $QbAnalytic = CustomQBAnalytic::where(['qb_id' => $request->qb_id, 'user_id' => $user])->first();
            } else {
                $QbAnalytic = QBAnalytic::where(['qb_id' => $request->qb_id, 'user_id' => $user])->first();

            }


            if (!empty($QbQuestion)) {

                //Get user answer wrong or right
                if ($QbQuestion->qb_answer == $request->option_id) {
                    $result = "true"; //given user answer is correct.
                } else {
                    $result = "false"; //given user answer is wrong.
                }
                if (empty($QbAnalytic)) {  
                    if ($request->qb_type == 1) { //1=>Custom QB,2=>Original QB
                        $data = CustomQBAnalytic::create([
                            'user_id' => $user = auth('api_user')->user()->id,
                            'user_answer' => $request->option_id,
                            'explanation' => $QbQuestion->qb_explanation,
                            'is_answer' => $result,
                            'attempt_time' => $request->attempt_time,
                            'qb_id' => $request->qb_id
                        ]);
                    } else {
                        $data = QBAnalytic::create([
                            'user_id' => $user = auth('api_user')->user()->id,
                            'user_answer' => $request->option_id,
                            'explanation' => $QbQuestion->qb_explanation,
                            'is_answer' => $result,
                            'attempt_time' => $request->attempt_time,
                            'qb_id' => $request->qb_id
                        ]);
                    }

                    $data->qb_right_answer = $QbQuestion->qb_answer; //question correct answer
                    // Calculate the poll percentage for each option and add it to the response.
                    $question_id = $request->qb_id;
                    $options = QuestionBankOption::where('qb_id', $question_id)->get();
                    $option_data = [];

                    $total_user_answer_count = ($request->qb_type == 1)
                        ? CustomQBAnalytic::where('qb_id', $question_id)->count()
                        : QBAnalytic::where('qb_id', $question_id)->count();

                    foreach ($options as $option) {
                        $total_option_count = ($request->qb_type == 1)
                            ? CustomQBAnalytic::where(['qb_id' => $question_id, 'user_answer' => $option->id])->count()
                            : QBAnalytic::where(['qb_id' => $question_id, 'user_answer' => $option->id])->count();
                         $check_selected_option_custom = CustomQBAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();
                        $check_selected_option_original = QBAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();

                        $check_selected_option = ($request->qb_type == 1) ? $check_selected_option_custom : $check_selected_option_original;
                        $poll_percent = ($total_user_answer_count > 0) ? ($total_option_count / $total_user_answer_count) * 100 : 0;
                           $option_image_url = ''; 

                        if (isset($option->option_image) && $option->option_image) {
                            
                            $option_image_url =  asset('public/images/question_bank/option_image/').'/'  . $option->option_image;
                        }
                        $option_data[] = [
                            'option_id' => $option->id,
                            'title' => $option->option,
                            'option_image'=>$option_image_url,
                            'poll_percent' => $poll_percent,
                            'select_status' => ($check_selected_option) ? 1 : 0,
                        ];
                    }

                    $data->option = $option_data; 
                    return res_success('Success', $data);
                } else {
                    return res_failed('you have already attempt this question.');
                }

            } else {
                return res_failed('Question does not exist');
            }

            //  return $data; die;

        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/questions-unattempt",
    *     tags={"Custom Question Bank"},
    *     summary="UnAttempt Custom  Test",
    *     description="UnAttempt custom question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="questionUnAttempt",
    *   @OA\Parameter(
    *         name="qb_id",
    *         in="query",
    *         description="Enter qb id ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *           
    *            
    *         )
    *     ),
    *   @OA\Parameter(
    *          name="qb_type",
    *          required=true,
    *          description="1=>Custom QB,2=>Original QB",
    *          in="query",     
    *          @OA\Schema( 
    *              type="array", 
    *              @OA\Items(type="enum", enum={1,2}),
    *              
    *          )
    *      ),
    *    @OA\Parameter(
    *          name="attempt_time",
    *          required=false,
    *          description="Question attemption time",
    *          in="query",     
    *          @OA\Schema( 
    *              type="string", 
    *              
    *              
    *          )
    *      ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    //un-attempt questions
    public function questionUnAttempt(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'qb_id' => 'required',
                'qb_type' => 'required',
                'attempt_time' => 'nullable',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            //Get daily mcq questions
            $QbQuestion = QuestionBank::where(['id' => $request->qb_id])->first();
            if ($request->qb_type == 1) { //1=>Custom QB,2=>Original QB
                //Get attempted users answer data
                $QbAnalytic = CustomQBAnalytic::where(['qb_id' => $request->qb_id, 'user_id' => $user])->first();
            } else {
                $QbAnalytic = QBAnalytic::where(['qb_id' => $request->qb_id, 'user_id' => $user])->first();

            }
// dd($request->all());

            if (!empty($QbQuestion)) {

                //Get user answer wrong or right
                /* if ($QbQuestion->qb_answer == $request->option_id) {
                $result = "true"; //given user answer is correct.
                } else {
                $result = "false"; //given user answer is wrong.
                }*/
                if (empty($QbAnalytic)) {
                    if ($request->qb_type == 1) { //1=>Custom QB,2=>Original QB
                        $data = CustomQBAnalytic::create([
                            'user_id' => $user = auth('api_user')->user()->id,
                            'user_answer' => $QbQuestion->qb_answer,
                            'explanation' => $QbQuestion->qb_explanation,
                            'is_answer' => 'true',
                            'is_unattempt_answer' => 1,
                            'attempt_time' => $request->attempt_time,
                            'qb_id' => $request->qb_id
                        ]);
                    } else {
                        $data = QBAnalytic::create([
                            'user_id' => $user = auth('api_user')->user()->id,
                            'user_answer' => intval($QbQuestion->qb_answer),
                            'explanation' => $QbQuestion->qb_explanation,
                            'is_answer' => 'true',
                            'is_unattempt_answer' => 1,
                             'attempt_time' => $request->attempt_time,
                            'qb_id' => $request->qb_id
                        ]);
                    }

                    $data->qb_right_answer = $QbQuestion->qb_answer; //question correct answer
                    return res_success('Success', $data);
                } else {
                    return res_failed('you have missed this question.');
                }

            } else {
                return res_failed('Question does not exist');
            }

            //  return $data; die;

        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /*
     * End Custom Question Bank
     */

    /**
    *  @OA\Get(
    *     path="/api/select-subject-original-qb",
    *     tags={"Question Bank"},
    *     summary="Question bank",
    *     description="Question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="selectSubjectForOriginQB",
    *    @OA\Parameter(
    *      name="subject_id",
    *      in="query",
    *      description="Enter subject id.",
    *      required=true,
    *      @OA\Schema(
    *        type="integer",
    *        default= "1"
    *      )
    *    ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function selectSubjectForOriginQB(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'subject_id' => 'required|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            //Get module wise topic list
            $userId = auth('api_user')->user()->id;
            $selecSubject = QuestionBank::where('subject_id', $request->subject_id)->groupBy('module_id')->pluck('module_id')->toArray();
            // dd($selecSubject);
            /* $subjectWithmodule = Subject::where('id', $selecSubject)->select('id', 'subject_name')->with([
            'module' => function ($query) use ($request) {
            $query->where('subject_id', $request->subject_id)->select('id', 'subject_id', 'module')->with('topics');
            }
            ])->get();*/

            $subjectWithmodule = Module::with([
                'topics' => function ($query) {
                    $query->select('id', 'module_id', 'topic')->withCount('questionBank as question_count')->having('question_count', '>', 0);    
                }
            ])->whereIn('id', $selecSubject)->select('id', 'subject_id', 'module')->get();


            foreach ($subjectWithmodule as $key => $value) {

                foreach ($value->topics as $key => $val) {
                    
                     $val->question_count = intval($val->question_count); 

                    $topicId = $val->id;
                    //Result
                    $result = QuestionBankResult::where(['topic_id' => $topicId, 'user_id' => $userId])->first();

                    //Get the user attempted questions
                    $data = Helper::result($topicId, $userId);

                    if($data){
                        //How many question are attempted count
                        if ($data['attemptedQuestion'] != 0) {
                            $val->attempt_question_count = $data['attemptedQuestion'];

                             
                        } else {
                            $val->attempt_question_count = 0;

                        }
                    }

                    //when user attempt the all question and data save in the result table            
                    if ($result) {
                        $val->is_attempt = 1;
                    } else {
                        $val->is_attempt = 0;
                    }
                }

            }


            return res_success('Success', $subjectWithmodule);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/topic-question-count",
     *     tags={"Question Bank"},
     *     summary="Question bank",
     *     description="Question bank",
     *     security={{"bearerAuth":{}}},
     *     operationId="moduleQuestionCount",
     *    @OA\Parameter(
     *      name="topic_id",
     *      in="query",
     *      description="Enter topic id.",
     *      required=true,
     *      @OA\Schema(
     *        type="integer",
     *        default= "1"
     *      )
     *    ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * 
     * )
     */
    public function topicQuestionCount(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'topic_id' => 'required|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $result = QuestionBankResult::where(['topic_id' => $request->topic_id, 'user_id' => auth('api_user')->user()->id])->first();

            if ($result) {

                $data = Helper::result($request->topic_id, auth('api_user')->user()->id);
                return res_success('Success', $data);
                exit;

            }
            //Get questions list topic wise
            $selecTopic = QuestionBank::where('question_banks.topic_id', $request->topic_id)->select('question_banks.*', 'subjects.subject_name', 'subjects.id')
                ->join('subjects', 'question_banks.subject_id', '=', 'subjects.id')
                ->join('modules', 'question_banks.module_id', '=', 'modules.id')
                ->join('topics', 'question_banks.topic_id', '=', 'topics.id')
                ->select('modules.id as module_id', 'subjects.subject_name', 'subjects.id as subject_id', 'modules.module', 'topics.id as topic_id', 'topics.topic')
                ->groupBy('subjects.subject_name')
                ->get();

            /// echo "<pre>"; print_r($selecTopic); die; 
            foreach ($selecTopic as $key => $value) {

                  $value->module_id = intval($value->module_id);
                  $value->subject_id = intval($value->subject_id);
                  $value->topic_id = intval($value->topic_id);
                $value->question_count = QuestionBank::where(['subject_id' => $value->subject_id, 'topic_id' => $value->topic_id])->count();
            }


            return res_success('Success', $selecTopic);
        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/original-qb-list",
    *     tags={"Question Bank"},
    *     summary="Question bank",
    *     description="Question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="originalQbQuestionList",
    *    @OA\Parameter(
    *      name="topic_id",
    *      in="query",
    *      description="Enter topic id.",
    *      required=true,
    *      @OA\Schema(
    *        type="integer",
    *        default= "1"
    *      )
    *    ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    //get questions for original question bank
    public function originalQbQuestionList(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'topic_id' => 'required|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $questionData = QuestionBank::where('topic_id', $request->topic_id)->pluck('id')->toArray();

            //Get where user not attempt the daily mcq
            $questionBank = QuestionBank::whereIn('id', $questionData)->get();

            if (!empty($questionBank)) {
                $questionArray = [];
                foreach ($questionBank as $key => $value) {
                    if($value->qb_explanation == null ){
                $value->qb_explanation = "";
            }
                        $question_image_url = ''; 
                        if ($value->question_image) {
                            $value->question_image = asset('images/question_bank/question_image/' . $value->question_image);
                        }
                        $explanation_image_url = ''; 
                        if ($value->explanation_image) {
                    
                            $value->explanation_image = asset('images/question_bank/explanation_image/' . $value->explanation_image);
                        }
                        $reference_image_url = '';
                        if ($value->refrence_image) {
                        //   echo "1234567"; die;
                            $value->refrence_image = asset('images/question_bank/refrence/' . $value->refrence_image);
                        }
                     $topicId = $value->topic_id;
                    //Bookmark
                    $value->is_bookmark = BookmarkQuestionBank::where(['qb_id' => $value->id, 'user_id' => $user])->pluck('is_selected')->first();
                    if ($value->is_bookmark == null) {
                        $value->is_bookmark = 0;
                    }
                    //get question related options
                    $options = QuestionBankOption::where('qb_id', $value->id)->get();

                   
                    $total_user_answer_count = QBAnalytic::where(['qb_id'=> $value->id,"is_unattempt_answer"=>0])->count();
                    $optionsk = [];
                    if ($options->count() > 0) {
                        foreach ($options as $k => $option) {

                            $totla_option_count = QBAnalytic::where(['qb_id' => $value->id, 'user_answer' => $option->id])->count();
                            $check_selected_option = QBAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();
                            if ($totla_option_count > 0) {
                                if($total_user_answer_count>0){
                                      $poll_percent = ($totla_option_count / $total_user_answer_count) * 100;
                                }else{
                                    $poll_percent = 0;
                                }
                              
                                
                                
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
                                'poll_percent' => round($poll_percent),
                                //'total_post_count' => count($options),
                                // 'option_count' => $totla_option_count,
                                'select_status' => ($check_selected_option) ? 1 : 0,
                                 'option_image' => $option_image_url, 

                            ];
                        }



                    }
                    //check user that mcq_question attempted or not
                    $check_selected_option = QBAnalytic::where(['user_id' => $user, 'qb_id' => $value->id])->first();

                    

                   
                    if ($check_selected_option) {
                        $value->is_attempt = 1;
                        $value->user_answer = $check_selected_option->user_answer;
                    } else {
                        $value->is_attempt = 0;
                        $value->user_answer = null;
                    }
                    //Check user unattempt = 0 and is_attempt=>1 
                    $unattemptedQuestionsCheck = QBAnalytic::where(['user_id' => $user, 'qb_id' => $value->id,'is_unattempt_answer'=>1])->first();
                    if($unattemptedQuestionsCheck){
                         $value->is_unattempt_answer = 1;
                    }else{
                         $value->is_unattempt_answer = 0;

                    }
                    $value->option = $optionsk;
                    $questionArray[] = $value;

                }
            }
            if(isset($topicId)){
            $topicName = Topic::where('id',$topicId)->pluck('topic')->first();
            }
            $data = [
                "topic_name"=>@$topicName,
                "qb_question" => $questionArray
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/qb-bookmark",
    *     tags={"Question Bank"},
    *     summary="Question bank",
    *     description="Bookmark Question Bank",
    *     security={{"bearerAuth":{}}},
    *     operationId=" ",
    *    @OA\Parameter(
    *      name="qb_id",
    *      in="query",
    *      description="Enter question bank id.",
    *      required=true,
    *      @OA\Schema(
    *        type="integer",
    *        default= "1"
    *      )
    *    ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function qbBookmark(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'qb_id' => 'required',
            ]);

            if ($validation->fails()) {

                return res_failed('QB id is required.');
            }
            $qbCheck = QuestionBank::where(['id' => $request->qb_id])->first();
            if (empty($qbCheck)) {
                return res_failed1('QB not found.');
            }
            $usercheck = BookmarkQuestionBank::where(['qb_id' => $request->qb_id, 'user_id' => auth('api_user')->user()->id])->first();
            if (!$usercheck) {
                $qb = BookmarkQuestionBank::create([
                    'user_id' => auth('api_user')->user()->id,
                    'is_selected' => 1,
                    'qb_id' => $request->qb_id
                ]);
            } else {

                $qb = BookmarkQuestionBank::where(['qb_id' => $request->qb_id, 'user_id' => auth('api_user')->user()->id])->update(['is_selected' => !$usercheck->is_selected]);


            }

            return res_success('Success', []);

        } catch (Exception $e) {
            // return $e->getMessage();
            // die;
            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/original-qb-result",
    *     tags={"Question Bank"},
    *     summary="Question bank",
    *     description="Question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="result",
    *    @OA\Parameter(
    *      name="topic_id",
    *      in="query",
    *      description="Enter topic id.",
    *      required=true,
    *      @OA\Schema(
    *        type="integer",
    *        default= "1"
    *      )
    *    ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function result(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'topic_id' => 'required|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $topicId = $request->topic_id;
            $userId = auth('api_user')->user()->id;

            $result = Helper::result($topicId, $userId);
            $result['topic_name'] = Topic::where('id',$topicId)->pluck('topic')->first();
            
            //  dd($result);
            if (count($result) > 0) {
                  if(($result['attemptedQuestion'] == 0) && ($result['unattemptedQuestion'] == 0)){
                    
                    return res_failed('Please attempt the question.');
                }
                $insert = QuestionBankResult::updateOrCreate([
                    'user_id' => $userId,
                    'subject_id' => $result['subjectId'],
                    'topic_id' => $result['topic_id']
                ], [
                        'user_id' => $userId,
                        'subject_id' => $result['subjectId'],
                        'topic_id' => $result['topic_id'],
                        'total_question' => $result['totalQuestion'],
                        'attempted_question' => $result['attemptedQuestion'],
                        'incorrect_answer' => $result['incorrectAnswer'],
                        'correct_answer' => $result['correctAnswer'],
                        'accuracy' => $result['accuracy']
                    ]);
                return res_success('Success', $result);
                /*  $data = [
                "result" => $result
                ];*/
            } else {
                $data = [];
                return res_success('Success', $data);
            }

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/result-custom-question-bank",
     *     tags={"Custom Question Bank"},
     *     summary="Custom Question bank",
     *     description="Custom Question bank",
     *     security={{"bearerAuth":{}}},
     *     operationId="customQuestionBankResult",
     * 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * ),
     * 
     * )
     */

    public function customQuestionBankResult(Request $request)
    {
        try {

            $userId = auth('api_user')->user()->id;
            $qustionAnalytics = DB::table('question_banks')
                ->join('custom_q_b_analytics', function ($join) use ($userId) {
                    $join->on('question_banks.id', '=', 'custom_q_b_analytics.qb_id')
                        ->where('custom_q_b_analytics.user_id', '=', $userId);
                })

                ->select('question_banks.module_id', 'question_banks.topic_id', 'question_banks.subject_id', 'custom_q_b_analytics.qb_id', 'custom_q_b_analytics.is_answer', 'custom_q_b_analytics.attempt_time', 'custom_q_b_analytics.is_unattempt_answer')->get()
            ;
             if(count($qustionAnalytics)>0){
              $subjectCount = $qustionAnalytics->groupBy('subject_id')->count();
            $topicCount = $qustionAnalytics->count();
            $totalQuestion = $qustionAnalytics->count();
            $incorrectAnswer = $qustionAnalytics->where('is_answer', 'false')->count();
            $correctAnswer = $qustionAnalytics->where("is_unattempt_answer",0)->where('is_answer', 'true')->count();
            // dd($correctAnswer);
            $accuracy = round($correctAnswer * 100 / $totalQuestion, 2) . "%";
          
            // Calculate total time taken to attempt all questions in seconds
            $totalTimeInSeconds = $qustionAnalytics->sum(function ($item) {
                
                $timeComponents = explode(':', $item->attempt_time);
              
                return ($timeComponents[0] * 3600) + ($timeComponents[1] * 60) + $timeComponents[2];
            });
             

            // Calculate average time taken per question (speedPerQuestion) in seconds
            $speedPerQuestion = ($totalTimeInSeconds / $totalQuestion);
            $data = [
                "subjectCount" => $subjectCount,
                "topicCount" => $topicCount,
                "totalQuestion" => $totalQuestion,
                "incorrectAnswer" => $incorrectAnswer,
                "correctAnswer" => $correctAnswer,
                "accuracy" => $accuracy,
                "speedPerQuestion" => round($speedPerQuestion, 2), 

            ];

            $insert = CustomQuestionBankResult::updateOrCreate([
                'user_id' => $userId
            ], [
                    'user_id' => $userId,
                    'subject_count' => $subjectCount,
                    'topic_count' => $topicCount,
                    'total_question' => $totalQuestion,
                    'incorrect_answer' => $incorrectAnswer,
                    'correct_answer' => $correctAnswer,
                    'accuracy' => $accuracy,
                    'speed_per_question' => $speedPerQuestion,

                ]);
            return res_success('Success', $data);
               
            }else{
                 return res_failed1('You have not attempt any question.');

            }
            return res_success('Success', $data);
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Delete(
    *     path="/api/discard-custom-question-bank",
    *     tags={"Custom Question Bank"},
    *     summary="Custom Question bank",
    *     description="Custom Question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="discardCustomQuestionBank",
    * 
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function discardCustomQuestionBank()
    {
        try {
            $discardCustomQB = CustomQuestionBank::where('user_id', auth('api_user')->user()->id)->delete();
            $discardCustomQBAnalytic = CustomQBAnalytic::where('user_id', auth('api_user')->user()->id)->delete();
            $result = CustomQuestionBankResult::where('user_id', auth('api_user')->user()->id)->delete();
            return res_success('Custom question bank discarded sccussfully!');

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Delete(
    *     path="/api/discard-question-bank",
    *     tags={"Question Bank"},
    *     summary="Question bank",
    *     description="Question bank",
    *     security={{"bearerAuth":{}}},
    *     operationId="discardQuestionBank",
    * @OA\Parameter(
    *      name="topic_id",
    *      in="query",
    *      description="Enter topic id.",
    *      required=true,
    *      @OA\Schema(
    *        type="integer",
    *        default= "1"
    *      )
    *    ),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function discardQuestionBank(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'topic_id' => 'required|min:0',

            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $topicId = $request->topic_id;
            $userId = auth('api_user')->user()->id;
             $qustionAnalytics = DB::table('question_banks')
                    ->join('q_b_analytics', function ($join) use ($userId,$topicId) {
                        $join->on('question_banks.id', '=', 'q_b_analytics.qb_id')
                             ->where('q_b_analytics.user_id', '=', $userId);
                    })
                    
                    ->select('question_banks.module_id','question_banks.topic_id','question_banks.subject_id', 'q_b_analytics.qb_id', 'q_b_analytics.is_answer')
                             ->where('question_banks.topic_id', $topicId)->pluck('qb_id')->toArray();
            $data = Helper::result($topicId, $userId);
             $discardQuestionBankAnalytic = QBAnalytic::whereIn('qb_id',$qustionAnalytics)->where(['user_id'=>auth('api_user')->user()->id])->delete();
            $result = QuestionBankResult::where(['user_id' => auth('api_user')->user()->id, 'topic_id' => $topicId])->delete();
            return res_success('Question bank discarded sccussfully!');

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
}