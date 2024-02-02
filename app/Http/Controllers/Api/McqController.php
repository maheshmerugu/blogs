<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\McqQuestion;
use App\Models\McqAnalytic;
use App\Models\McqOption;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class McqController extends Controller
{
    /**
    *  @OA\Get(
    *     path="/api/missed-mcq",
    *     tags={"Mcq Screen"},
    *     summary="Missed Mcq Data",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="missedMcq",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function missedMcq()
    {
        try {
            $user = auth('api_user')->user()->id;
            // dd($user);
            //Get mcq-id from users answer table(mcq_analytics)
            $mcqId = McqAnalytic::where('user_id', auth('api_user')->user()->id)
                ->pluck('mcq_id');
      $currentDate = Carbon::now();
                $formattedDate = $currentDate->format('Y-m-d');
            //Get where user not attempt the daily mcq
            $mcqAnalytic = McqQuestion::select('mcq_questions.*')
                 ->where('status',1)
                ->where('mcq_date','<',$formattedDate)
                ->where('year_id',auth('api_user')->user()->year_id)
                ->whereNotIn('id', $mcqId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            if (!empty($mcqAnalytic)) {
                $questionArray = [];
                foreach ($mcqAnalytic as $key => $value) {
                     if($value->question_image){
                         $value->question_image = asset('public/images/mcq_image/question_image/').'/'  . $value->question_image;
                    }else{
                         $value->question_image  = "";
                    }
                   if( $value->explanation_image){
                         $value->explanation_image = asset('public/images/mcq_image/explanation_image/').'/'  . $value->explanation_image;
                   }else{
                       $value->explanation_image ="";
                   }
                    //get question related options
                    $options = McqOption::where('mcq_id', $value->id)->get();

                    $total_user_answer_count = McqAnalytic::where('mcq_id', $value->id)->count();
                    $optionsk = [];
                    if ($options->count() > 0) {
                        foreach ($options as $k => $option) {

                            $totla_option_count = McqAnalytic::where(['mcq_id' => $value->id, 'user_answer' => $option->id])->count();
                            $check_selected_option = McqAnalytic::where(['user_id' => $user, 'user_answer' => $option->id])->first();
                            if ($totla_option_count > 0) {
                                $poll_percent = ($totla_option_count / $total_user_answer_count) * 100;
                            } else {
                                $poll_percent = 0;
                            }
                             $option_image_url = ''; 

                        if (isset($option->option_image) && $option->option_image) {
                            
                            $option_image_url =  asset('public/images/mcq_image/options/').'/'  . $option->option_image;
                        }
                            $optionsk[] = [

                                'option_id' => $option->id,
                                'title' => $option->option,
                                'poll_percent' => round($poll_percent),
                                'option_image' => $option_image_url,
                                //'total_post_count' => count($options),
                                // 'option_count' => $totla_option_count,
                                'select_status' => ($check_selected_option) ? 1 : 0,
                            ];
                        }



                    }
                    //check user that mcq_question attempted or not
                    $check_selected_option = McqAnalytic::where(['user_id' => $user, 'mcq_id' => $value->id])->first();
                    if ($check_selected_option) {
                        $value->is_attempt = 1;
                    } else {
                        $value->is_attempt = 0;
                    }
                    $value->option = $optionsk;
                    $questionArray[] = $value;
                }
            }
            $data['mcq_question'] = $questionArray;
             // Get the current page URL
        $currentPageUrl = $mcqAnalytic->url($mcqAnalytic->currentPage());
         // Get the next page URL
        $nextPageUrl = $mcqAnalytic->nextPageUrl();
        // Get the total page count
        $totalPages = $mcqAnalytic->lastPage();
        $data['next_page_url'] = $nextPageUrl;
        $data['current_page_url'] = $currentPageUrl;
        $data['total'] = $totalPages;

            return res_success('Success', $data);
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/attempt-mcq",
    *     tags={"Mcq Screen"},
    *     summary="Attempt Mcq Test",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="attemptMcq",
    *       @OA\Parameter(
    *         name="option_id",
    *         in="query",
    *         description="Enter option ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="number",
    *           
    *            
    *         )
    *     ),
    *   @OA\Parameter(
    *         name="mcq_id",
    *         in="query",
    *         description="Enter mcq id ",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="number",
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
    
    * 
    * )
    */
    public function attemptMcq(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'mcq_id' => 'required',
                'option_id' => 'required',
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
            $McqQuestion = McqQuestion::where(['id' => $request->mcq_id])->first();

            //Get attempted users answer data
            $McqAnalytic = McqAnalytic::where(['mcq_id' => $request->mcq_id, 'user_id' => $user])->first();
            //dd($McqAnalytic);
            if (!empty($McqQuestion)) {

                //Get user answer wrong or right
                if ($McqQuestion->mcq_answer == $request->option_id) {
                    $result = "true"; //given user answer is correct.
                } else {
                    $result = "false"; //given user answer is wrong.
                }
                if (empty($McqAnalytic)) {
                    $data = McqAnalytic::create([
                        'user_id' => $user = auth('api_user')->user()->id,
                        'user_answer' => $request->option_id,
                        'explanation' => $McqQuestion->mcq_explanation,
                        'is_answer' => $result,
                        'mcq_answer' => $McqQuestion->mcq_answer,
                        'mcq_id' => $request->mcq_id
                    ]);
                    $data->mcq_answer = $McqQuestion->mcq_answer; //question correct answer
                    return res_success('Success', $data);
                } else {
                    return res_failed('you have already attempt this question.');
                }

            } else {
                return res_failed('Mcq id does not exist');
            }

            //  return $data; die;

        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }
}