<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\HandWrittingNotes;
use App\Models\Module;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\Video;
use App\Models\VideoBookmark;
use App\Models\VideoDownload;
use App\Models\VideoPlayStatus;
use App\Models\VideoRating;
use App\Models\Year;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{

    /**
     *  @OA\Get(
     *     path="/api/get-subjects",
     *     tags={"Videos"},
     *     summary="All Subjects",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="getSubject",
     *     @OA\Parameter(
     *          name="video_type",
     *          required=true,
     *          description="array of type numbers 1=>theory 2=>labs",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="enum", enum={1,2}),
     *
     *          )
     *      ),
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
    // Get All subjects of theory and labs {'1'=>theory,'2'=>labs}
    public function getSubject(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_type' => 'required',
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

            // $subjects = Subject::where(['video_type' => $request->video_type, 'year_id' => $request->year_id])
            // ->where('subject_name', 'LIKE', "%{$request->search}%")
            // ->select('id', 'year_id', 'subject_name')
            // ->withCount('video')
            // ->having('video_count', '>', 0)
            // ->get();
            $subjects = Subject::where(['video_type' => $request->video_type, 'year_id' => $request->year_id])
                ->where('subject_name', 'LIKE', "%{$request->search}%")
                ->select('id', 'year_id', 'subject_name')
                ->withCount(['video' => function ($query) {
                    $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('teachers')
                            ->whereColumn('teachers.id', 'videos.teacher_id');
                    });
                }])
                ->having('video_count', '>', 0)
                ->get();
            foreach ($subjects as $value) {
                $value->video_count = intval($value->video_count);
            }
            //Get subscription status of the user
            $subscriptionCheck = Helper::checkUserSubscriptionForVideo();
            if ($subscriptionCheck) {
                if (auth('api_user')->user()->year_id == $request->year_id) {
                    $is_subscribed = 0;
                } else {
                    $is_subscribed = 1;
                }
            } else {
                $is_subscribed = 1;
            }
            $data = [
                'subjects' => $subjects,
                'is_subscribed' => $is_subscribed,
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
     *     path="/api/get-teachers",
     *     tags={"Videos"},
     *     summary="Teachers List",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="getTeachers",
     * @OA\Parameter(
     *         name="year_id",
     *         in="query",
     *         description="Enter Year id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="subject_id",
     *         in="query",
     *         description="Enter Subject id",
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
     *         name="search",
     *         in="query",
     *         description="Search teacher",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="batra",
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
    // Get all subject wise teacher
    public function getTeachers(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'year_id' => 'required',
                'subject_id' => 'required',
                "search" => 'nullable|string|max:25',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $awsUrl = env('AWS_URL');
            $teachers = Teacher::where(['subject_id' => $request->subject_id, 'year_id' => $request->year_id])
                ->where('teacher_name', 'LIKE', "%{$request->search}%")
                ->select('id', 'year_id', 'subject_id', 'teacher_image', 'teacher_name', 'qualification')
                ->withCount('video')
                ->having('video_count', '>', 0)
                ->get();

            //video paused or complete 1=>paused,2=>complete
            $user = auth('api_user')->user()->id;

            foreach ($teachers as $key => $value) {
                if ($value->teacher_image) {
                    $value->teacher_image = asset('images/teacher/') . '/' . $value->teacher_image;
                } else {
                    $value->teacher_image = "";
                }
                $value->video_count = intval($value->video_count);
                $video = Video::where('teacher_id', $value->id)->pluck('id')->toArray();
                $subscriptionCheck = Subscription::where(['user_id' => $user, 'subcription_for' => 1])->orderBy('id', 'desc')->first();
                $videoPlayStatusDetail = VideoPlayStatus::whereIn('video_id', $video)->where(['user_id' => $user, 'subcription_id' => $subscriptionCheck->id])->count();

                //user how many video watch count
                $value->progress_video_count = $videoPlayStatusDetail;
            }

            $data = [
                'teachers' => $teachers,
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    public function getsubTeachers(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'year_id' => 'required',
                'subject_id' => 'required',
                "search" => 'nullable|string|max:25',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $awsUrl = env('AWS_URL');
            $teachers = Teacher::where(['subject_id' => $request->subject_id, 'year_id' => $request->year_id])
                ->where('teacher_name', 'LIKE', "%{$request->search}%")
                ->select('id', 'year_id', 'subject_id', 'teacher_image', 'teacher_name', 'qualification')
                ->withCount('video')
                ->having('video_count', '>', 0)
                ->get();

            //video paused or complete 1=>paused,2=>complete
            $user = auth('api_user')->user()->id;

            foreach ($teachers as $key => $value) {
                if ($value->teacher_image) {
                    $value->teacher_image = asset('images/teacher/') . '/' . $value->teacher_image;
                } else {
                    $value->teacher_image = "";
                }
                $value->video_count = intval($value->video_count);
                $video = Video::where('teacher_id', $value->id)->pluck('id')->toArray();

                $videoPlayStatusDetail = VideoPlayStatus::whereIn('video_id', $video)->where(['user_id' => $user])->count();

                //user how many video watch count
                $value->progress_video_count = $videoPlayStatusDetail;
            }

            $data = [
                'teachers' => $teachers,
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
     *     path="/api/get-topics",
     *     tags={"Videos"},
     *     summary="Topic List",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="getTopics",
     * @OA\Parameter(
     *         name="year_id",
     *         in="query",
     *         description="Enter Year id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     * @OA\Parameter(
     *         name="video_status",
     *         in="query",
     *         description="Enter video status ( 0=>All 1=>paused 2=>completed 3=>not started)",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="subject_id",
     *         in="query",
     *         description="Enter Subject id",
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
     *         name="teacher_id",
     *         in="query",
     *         description="Enter teacher id",
     *         required=false,
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
    //Get all Teacher wise topics
    public function getTopics(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'year_id' => 'required',
                'subject_id' => 'required',
                'teacher_id' => 'required',
                'video_status' => 'nullable|in:0,1,2,3',
                "search" => 'nullable|string|max:25',
            ]);

            if ($data->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];

                return response()->json($result);
            }

            $user = auth('api_user')->user()->id;

            // $subject = Subject::where(['id' => $request->subject_id])
            //     ->select('id', 'year_id', 'subject_name')
            //     ->withCount('video')
            //     ->withSum('video', 'video_total_time')
            //     ->first();
            $teacherId = $request->teacher_id;
            $subject = Subject::where('id', $request->subject_id)
                ->select('id', 'year_id', 'subject_name')
                ->withCount(['video' => function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                }])
                ->withSum(['video' => function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                }], 'video_total_time')
                ->with(['video' => function ($query) use ($teacherId) {
                    $query->select('*')->where('teacher_id', $teacherId);
                }])
                ->first();
            $subject->video_sum_video_total_time = strval($subject->video_sum_video_total_time);
            // echo "<pre>"; print_r($subject); die;
            $subscriptionCheck = Subscription::where(['user_id' => $user, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

            $subject->video_count = intval($subject->video_count);
            $subject->teacher_name = Teacher::where(['id' => $request->teacher_id])->pluck('teacher_name')->first();

            $module = Module::where([
                'subject_id' => $request->subject_id,
                'year_id' => $request->year_id,
                'teacher_id' => $request->teacher_id,
            ])->with([
                'topics' => function ($q) use ($request, $user, $subscriptionCheck) {
                    $q->with(['video' => function ($qs) use ($request, $user, $subscriptionCheck) {
                        if ($request->video_status == 1) {
                            $qs->whereIn('id', function ($query) use ($user, $subscriptionCheck) {
                                $query->select('video_id')
                                    ->from('video_play_statuses')
                                    ->where(['user_id' => $user, 'status' => 1, 'subcription_id' => $subscriptionCheck->id]);
                            });
                        } elseif ($request->video_status == 2) {
                            $qs->whereIn('id', function ($query) use ($user, $subscriptionCheck) {
                                $query->select('video_id')
                                    ->from('video_play_statuses')
                                    ->where(['user_id' => $user, 'status' => 2, 'subcription_id' => $subscriptionCheck->id]);
                            });
                        } elseif ($request->video_status == 3) {
                            $qs->whereNotIn('id', function ($query) use ($user, $subscriptionCheck) {
                                $query->select('video_id')
                                    ->from('video_play_statuses')
                                    ->where(['user_id' => $user, 'subcription_id' => $subscriptionCheck->id]);
                            });
                        } else {
                            $qs->where('status', 1);
                        }
                    }]);
                    $q->whereHas('video', function ($query) {
                        $query->where('status', 1);
                    });
                },
            ]);

            $module->when($request->video_status == 0, function ($q) {
                $q->with(['topics' => function ($q) {
                    $q->whereHas('video', function ($query) {
                        $query->where('status', 1);
                    });
                }]);
            });

            $module = $module->get()->filter(function ($module) {
                $module->topics = $module->topics->filter(function ($topic) {
                    return $topic->video->isNotEmpty();
                });

                return $module->topics->isNotEmpty();
            });
            $module = $module->values();
            $data = [
                'subject' => $subject,
                'module' => $module,
            ];

            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    //Get all Teacher wise topics
// Get all Teacher wise topics for subscribed user
    public function getTopicssub(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'year_id' => 'required',
                'subject_id' => 'required',
                'teacher_id' => 'required',
                'video_status' => 'nullable|in:0,1,2,3',
                'search' => 'nullable|string|max:25',
            ]);

            if ($data->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (object) [];

                return response()->json($result);
            }

            $user = auth('api_user')->user();

            $teacherId = $request->teacher_id;
            $subject = Subject::where('id', $request->subject_id)
                ->select('id', 'year_id', 'subject_name')
                ->withCount(['video' => function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                }])
                ->withSum(['video' => function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                }], 'video_total_time')
                ->with(['video' => function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                }])
                ->first();

            $subject->video_sum_video_total_time = strval($subject->video_sum_video_total_time);

            // Check if the user has an active subscription for the video's year with status = 1
            $subscriptionCheck = Subscription::where(['user_id' => $user->id, 'subcription_for' => $subject->year_id, 'status' => 1])->orderBy('id', 'desc')->first();

            $subject->video_count = intval($subject->video_count);
            $subject->teacher_name = Teacher::where(['id' => $request->teacher_id])->pluck('teacher_name')->first();

            $module = Module::where([
                'subject_id' => $request->subject_id,
                'year_id' => $request->year_id,
                'teacher_id' => $request->teacher_id,
            ])->with([
                'topics' => function ($q) use ($request, $user, $subscriptionCheck) {
                    $q->with(['video' => function ($qs) use ($request, $user, $subscriptionCheck) {
                        if ($subscriptionCheck) {
                            // If subscribed, retrieve all videos and set the 'locked' attribute to false
                            $qs->addSelect(['locked' => false]);
// Check if user_id is present in subscription table and status is 1
                            if ($subscriptionCheck->user_id == auth('api_user')->user()->id && $subscriptionCheck->status == 1) {
                                // If user is subscribed and status is 1, set video_type to 1
                                $qs->whereHas('subscriptions', function ($query) use ($subscriptionCheck) {
                                    $query->where(['user_id' => $subscriptionCheck->user_id, 'status' => 1]);
                                })->update(['video_type' => 1]);
                            }

                        } else {
                            // If not subscribed, check if the video_type is free or paid
                            $qs->addSelect(['locked' => DB::raw('CASE WHEN video_type = 1 THEN 0 ELSE 1 END')]);
                        }
                    }]);
                    $q->whereHas('video', function ($query) {
                        $query->where('status', 1);
                    });
                },
            ]);

            $module->when($request->video_status == 0, function ($q) {
                $q->with(['topics' => function ($q) {
                    $q->whereHas('video', function ($query) {
                        $query->where('status', 1);
                    });
                }]);
            });

            $module = $module->get()->filter(function ($module) {
                $module->topics = $module->topics->filter(function ($topic) {
                    return $topic->video->isNotEmpty();
                });

                return $module->topics->isNotEmpty();
            });
            $module = $module->values();
            $data = [
                'subject' => $subject,
                'module' => $module,
            ];

            return res_success('Success', $data);
        } catch (Exception $e) {
            return res_catch('Something went wrong!');
        }
    }

    //Get online vdo cipher video otp and playbackinfo
    private function vdoCipherVedioDetail($videos)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev.vdocipher.com/api/videos/$videos/otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "ttl" => 300,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Apisecret 6Df7MHXZExuUsQ8zy7KH05tYnmjQHK5ItdXDNYE3LfUNP7C3hxBnr04jnDXB5sbH",
                "Content-Type: application/json",
            ),
        )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $data[$i]['video_details'] = json_decode($err);
        } else {
            $data['video_details'] = json_decode($response);
            return [
                //'status' => true,
                'responseObj' => $data['video_details'],
            ];
            // return json_decode($response); die;
        }
    }
    //Get offline vdo cipher video otp and playbackinfo
    private function vdoCipherVedioDetailOffline($videos)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev.vdocipher.com/api/videos/$videos/otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'licenseRules' => json_encode([
                    'canPersist' => true,
                    'rentalDuration' => 15 * 24 * 3600,
                ]),
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Apisecret 6Df7MHXZExuUsQ8zy7KH05tYnmjQHK5ItdXDNYE3LfUNP7C3hxBnr04jnDXB5sbH",
                "Content-Type: application/json",
            ],
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $data[$i]['video_details'] = json_decode($err);
        } else {

            $data['video_details'] = json_decode($response);

            return [
                //'status' => true,
                'responseObj' => $data['video_details'],
            ];
            // return json_decode($response); die;
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/get-video-detail",
     *     tags={"Videos"},
     *     summary="Video Details",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="getVideoDetail",
     * @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
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

    //Get video detail
    public function getVideoDetail(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            //Get reqested video detail
            $user = auth('api_user')->user()->id;
            $video = Video::where(['id' => $request->video_id])->first();

            if (isset($video->doc_url)) {

                // $video->doc_url = env('AWS_URL').'auricle/documents/'.$video->doc_url;
                // $video->doc_url = DB::raw("CONCAT('" . asset('storage/app/').'/' . "', videos.doc_url) AS doc_url");
                //  dd($video);
                $video->doc_url = asset('storage/app/') . '/' . $video->doc_url;
                $doc_url_value = $video->doc_url;
                // dd($doc_url_value);
            } else {
                $video->doc_url = "";
            }
            if (empty($video)) {
                return res_failed1('Video not found.');
            }
            $topicDatails = Topic::where('id', $video->topic_id)->first();
            $video->video_title = ($video) ? $video->video_title : '';

            $teacherDetail = Teacher::where('id', $video->teacher_id)->first();
            $video->teacher_name = ($teacherDetail) ? $teacherDetail->teacher_name : '';
            $latestSubscription = Subscription::where('user_id', $user)
                ->orderBy('id', 'desc')
                ->first();
            $videoPlayDetail = VideoPlayStatus::where('video_id', $video->id)->where('subcription_id', $latestSubscription->id)->first();
            $video->video_play_time = ($videoPlayDetail) ? $videoPlayDetail->watch_time : '';
            $video->is_progress_video = ($videoPlayDetail) ? (string) $videoPlayDetail->is_progress_video : '';

            $videoRating = VideoRating::where('video_id', $video->id)->where('user_id', $user)->first();
            $video->video_rating = ($videoRating) ? $videoRating->rating : 0;

            $videos = $video->video_url;
            //Get bookmark video status
            $video->is_bookmark = VideoBookmark::where(['video_id' => $video->id, 'user_id' => $user])->pluck('is_selected')->first();
            $video->is_bookmark = intval($video->is_bookmark);
            if ($video->is_bookmark == null) {
                $video->is_bookmark = 0;
            }
            //Get handwritting data of the video
            $video->hand_writting_notes = HandWrittingNotes::where(['video_id' => $video->id, 'user_id' => $user])->orderBy('id', 'DESC')->get();

            $vdoCipherVedioDetail = $this->vdoCipherVedioDetail($videos);

            //Get up next videos
            $upNextVideos = Video::where('subject_id', $video->subject_id)->where('id', '>', $request->video_id)->select('id', 'teacher_id', 'topic_id', 'video_total_time', 'video_title')->limit(3)->get();
            foreach ($upNextVideos as $key => $value) {
                //  $videoUpNextVideoDetail =  $this->vdoCipherVedioDetail($value->video_url);

                /* //Get bookmark video status
                $value->is_bookmark = VideoBookmark::where(['video_id'=>$value->id,'user_id'=>$user])->pluck('is_selected')->first();
                if($value->is_bookmark == null){
                $value->is_bookmark = 0;
                }
                //Get handwritting data of the video
                $value->hand_writting_notes = HandWrittingNotes::where(['video_id'=>$value->id,'user_id'=>$user])->get();*/
                /*//up next videos data
                $value->video_details =  $videoUpNextVideoDetail;*/

                $topicDatails = Topic::where('id', $value->topic_id)->first();
                $value->video_title = ($value) ? $value->video_title : '';

                $teacherDetail = Teacher::where('id', $value->teacher_id)->first();
                $value->teacher_name = ($teacherDetail) ? $teacherDetail->teacher_name : '';
                $videoPlayDetail = VideoPlayStatus::where('video_id', $video->id)->first();
                $value->video_play_time = ($videoPlayDetail) ? $videoPlayDetail->watch_time : ''; //time in sec

            }

            //Requested video data
            $video->video_details = $vdoCipherVedioDetail;

            /*
             * Start subscription check for notes
             * Get subscription status of the user
             * 1=>suscribed,0=>un_suscribed
             */
            $subscriptionCheck = Helper::checkUserSubscriptionForNotes();
            if ($subscriptionCheck || $subscriptionCheck == 'null') {

                $is_suscribed_for_notes = 0;
            } else {

                $is_suscribed_for_notes = 1;
            }
            //end subscription for notes
            //Response
            $data = [
                'video' => $video,
                'upNextVideos' => $upNextVideos,
                'is_suscribed_for_notes' => $is_suscribed_for_notes,
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    public function getSubVideoDetail(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            //Get reqested video detail

            $video = Video::where(['id' => $request->video_id])->first();

            if (isset($video->doc_url)) {

                // $video->doc_url = env('AWS_URL').'auricle/documents/'.$video->doc_url;
                // $video->doc_url = DB::raw("CONCAT('" . asset('storage/app/').'/' . "', videos.doc_url) AS doc_url");
                //  dd($video);
                $video->doc_url = asset('storage' . '/' . $video->doc_url);
                $doc_url_value = $video->doc_url;
                // dd($doc_url_value);
            } else {
                $video->doc_url = "";
            }
            if (empty($video)) {
                return res_failed1('Video not found.');
            }
            $topicDatails = Topic::where('id', $video->topic_id)->first();
            $video->video_title = ($video) ? $video->video_title : '';

            $teacherDetail = Teacher::where('id', $video->teacher_id)->first();
            $video->teacher_name = ($teacherDetail) ? $teacherDetail->teacher_name : '';

            $videoPlayDetail = VideoPlayStatus::where(['video_id' => $video->id, 'user_id' => auth('api_user')->user()->id])->first();
            $video->video_play_time = ($videoPlayDetail) ? $videoPlayDetail->watch_time : '';
            $video->is_progress_video = ($videoPlayDetail) ? (string) $videoPlayDetail->is_progress_video : '';

            $videoRating = VideoRating::where(['video_id' => $video->id, 'user_id' => auth('api_user')->user()->id])->first();
            $video->video_rating = ($videoRating) ? $videoRating->rating : 0;

            $videos = $video->video_url;
            //Get bookmark video status
            $is_bookmark = VideoBookmark::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id])->pluck('is_selected')->first();
            $video_is_bookmark = intval($is_bookmark);
            //dd($video_is_bookmark);
            if ($video_is_bookmark == null) {
                $video->is_bookmark = 0;
            } else {
                $video->is_bookmark = $video_is_bookmark;
            }
            //Get handwritting data of the video
            $video->hand_writting_notes = HandWrittingNotes::where(['video_id' => $video->id, 'user_id' => auth('api_user')->user()->id])->orderBy('id', 'DESC')->get();

            $vdoCipherVedioDetail = $this->vdoCipherVedioDetail($videos);
            $subjectsDetail = Subject::where('id', $video->subject_id)->first();
            $video->subject_name = ($subjectsDetail) ? $subjectsDetail->subject_name : '';
            //Get up next videos
            $upNextVideos = Video::where('subject_id', $video->subject_id)->where('id', '>', $request->video_id)->select('id', 'subject_id', 'teacher_id', 'video_type', 'topic_id', 'video_total_time', 'video_title')->limit(3)->get();

            foreach ($upNextVideos as $key => $value) {
                //  $videoUpNextVideoDetail =  $this->vdoCipherVedioDetail($value->video_url);

                /* //Get bookmark video status
                $value->is_bookmark = VideoBookmark::where(['video_id'=>$value->id,'user_id'=>$user])->pluck('is_selected')->first();
                if($value->is_bookmark == null){
                $value->is_bookmark = 0;
                }
                //Get handwritting data of the video
                $value->hand_writting_notes = HandWrittingNotes::where(['video_id'=>$value->id,'user_id'=>$user])->get();*/
                /*//up next videos data
                $value->video_details =  $videoUpNextVideoDetail;*/

                $topicDatails = Topic::where('id', $value->topic_id)->first();
                $value->video_title = ($value) ? $value->video_title : '';

                $subjectsDetail = Subject::where('id', $value->subject_id)->first();
                $value->subject_name = ($subjectsDetail) ? $subjectsDetail->subject_name : '';

                $teacherDetail = Teacher::where('id', $value->teacher_id)->first();
                $value->teacher_name = ($teacherDetail) ? $teacherDetail->teacher_name : '';
                $videoPlayDetail = VideoPlayStatus::where('video_id', $video->id)->first();
                $value->video_play_time = ($videoPlayDetail) ? $videoPlayDetail->watch_time : ''; //time in sec

            }

            //Requested video data
            $video->video_details = $vdoCipherVedioDetail;

            /*
             * Start subscription check for notes
             * Get subscription status of the user
             * 1=>suscribed,0=>un_suscribed
             */
            $subscriptionCheck = Helper::checkUserSubscriptionForNotes();
            if ($subscriptionCheck || $subscriptionCheck == 'null') {

                $is_suscribed_for_notes = 0;
            } else {

                $is_suscribed_for_notes = 1;
            }
            //end subscription for notes
            //Response
            $data = [
                'video' => $video,
                'upNextVideos' => $upNextVideos,
                'is_suscribed_for_notes' => $is_suscribed_for_notes,
            ];
            return res_success('Success', $data);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/verify-otp-video",
     *     tags={"Videos"},
     *     summary="Verified video otp and playbackinfo",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="verifyOtpvideo",
     *  @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter video id",
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
    //verify otp for video
    public function verifyOtpvideo(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            //Get reqested video detail
            $user = auth('api_user')->user()->id;
            $video = Video::where(['id' => $request->video_id])->first();
            if (empty($video)) {
                return res_failed1('Video not found.');
            }
            $videos = $video->video_url;
            $vdoCipherVedioDetail = $this->vdoCipherVedioDetail($videos);
            return res_success('Success', $vdoCipherVedioDetail);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/video-bookmark",
     *     tags={"Videos"},
     *     summary="Video Bookmark",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="videoBookmark",
     * @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
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
    //User video book mark
    public function videoBookmark(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);

            if ($validation->fails()) {

                return res_failed('Video id is required.');
            }
            $videoCheck = Video::where(['id' => $request->video_id])->first();
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }
            $usercheck = VideoBookmark::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id])->first();
            if (!$usercheck) {
                $video = VideoBookmark::create([
                    'user_id' => auth('api_user')->user()->id,
                    'is_selected' => 1,
                    'video_id' => $request->video_id,
                ]);
            } else {

                $video = VideoBookmark::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id])->update(['is_selected' => !$usercheck->is_selected]);

            }

            return res_success('Success', []);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Post(
     *     path="/api/video-rating",
     *     tags={"Videos"},
     *     summary="Video Details",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="VideoRating",
     * @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     *  * @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Enter video rating",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="5",
     *
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Write review here..",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="Very Good!",
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
    //Video Rating
    // VideoRating controller
    public function VideoRating(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'video_id' => 'required',
                'rating' => 'required|numeric|min:1|max:5',
                'content' => 'required',
            ]);

            if ($validator->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validator->messages()->first();
                $result['data'] = (object) [];
                return response()->json($result);
            }

            // Check if the user is subscribed to a plan
            $user = auth('api_user')->user();
            if (!$user->isSubscribed()) {
                $result['code'] = 2;
                $result['status'] = 420;
                $result['message'] = 'Subscribe to our plan now.';
                $result['data'] = (object) [];
                return response()->json($result);
            }

            // Check if the video exists
            $videoCheck = Video::find($request->video_id);
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }

            // Update or create the video rating
            $data = VideoRating::updateOrCreate(
                [
                    'video_id' => $request->video_id,
                    'user_id' => $user->id,
                ],
                [
                    'rating' => $request->rating,
                    'content' => $request->content,
                ]
            );

            return res_success('Success', $data);
        } catch (Exception $e) {
            // Log the exception
            \Log::error($e);

            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Post(
     *     path="/api/video-hand-writting-notes",
     *     tags={"Videos"},
     *     summary="Video Details",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="HandWrittingNotes",
     * @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     *  * @OA\Parameter(
     *         name="video_time",
     *         in="query",
     *         description="Enter Video Time",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="1:30",
     *
     *         )
     *     ),
     *  * @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Type some thing here.",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="This is the notes.",
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
    //Create hand writting notes
    public function HandWrittingNotes(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'video_id' => 'required',
                'video_time' => 'nullable',
                'content' => 'required',
            ]);
            if ($validation->fails()) {

                return res_failed('Content is required.');
            }
            $videoCheck = Video::where(['id' => $request->video_id])->first();
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }
            $data = HandWrittingNotes::create([
                'user_id' => auth('api_user')->user()->id,
                'video_id' => $request->video_id,
                'video_time' => $request->video_time,
                'content' => $request->content,
            ]);

            return res_success('Success', $data);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    //Update handwritting notes
    /**
     *  @OA\Post(
     *     path="/api/edit-video-hand-writting-notes",
     *     tags={"Videos"},
     *     summary="Video Details",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="EditHandWrittingNotes",
     * @OA\Parameter(
     *         name="note_id",
     *         in="query",
     *         description="Enter Note id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     *  * @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Type some thing here.",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="This is the notes.",
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
    //Update handwriting notes
    public function EditHandWrittingNotes(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'note_id' => 'required | numeric',
                'content' => 'required',
            ], [
                'note_id.required' => 'Note Id is required.',
                'content.required' => 'Content is required.',
            ]);
            if ($validation->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $noteCheck = HandWrittingNotes::where(['id' => $request->note_id, 'user_id' => auth('api_user')->user()->id])->first();
            if (empty($noteCheck)) {
                return res_failed1('Note not found.');
            }
            if ($noteCheck) {
                $noteCheck->content = $request->content;
                $noteCheck->updated_at = Carbon::now();
                $noteCheck->save();
            }

            return res_success('Success', $noteCheck);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/video-play-status",
     *     tags={"Videos"},
     *     summary="Video Details",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="videoPlayStatus",
     * @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="integer",
     *             default="1",
     *
     *         )
     *     ),
     * @OA\Parameter(
     *         name="watch_time",
     *         in="query",
     *         description="Enter watch time(in second:).",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="3",
     *
     *         )
     *     ),
     *   @OA\Parameter(
     *         name="is_progress_video",
     *         in="query",
     *         description="Enter progress time(in second:).",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *
     *             type="string",
     *             default="32",
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
    //Countinue video watichng status

    public function subvideoPlayStatus(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
                'watch_time' => 'nullable',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;

            $videoCheck = Video::where(['id' => $request->video_id])->first();
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }
            $subscriptionCheck = Subscription::where(['user_id' => $user, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

            $videoPlayStatusDetail = VideoPlayStatus::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id])->first();
            //Add on watch time
            if ($videoPlayStatusDetail != null) {
                $watchTime = $videoPlayStatusDetail->watch_time + $request->watch_time;
            } else {

                $watchTime = $request->watch_time;
            }

            //video paused or complete 1=>paused,2=>complete
            if (@$videoPlayStatusDetail->watch_time >= $videoCheck->video_total_time || $watchTime >= $videoCheck->video_total_time) {

                $video_status = 2; // video fully watched
            } else {

                $video_status = 1; // video not fully watched yet

            }
            $data = VideoPlayStatus::updateOrCreate(
                [
                    'video_id' => $request->video_id,
                    'subcription_id' => $subscriptionCheck->id ?? 0,
                    'user_id' => $user,
                ],
                [
                    'subcription_id' => $subscriptionCheck->id ?? 0,
                    'video_id' => $request->video_id,
                    'is_progress_video' => $request->is_progress_video,
                    'watch_time' => $watchTime,
                    'status' => $video_status, //1=>paused 2=>completed
                    'user_id' => $user,
                ]
            );

            return res_success('Success', $data);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }

    public function videoPlayStatus(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
                'watch_time' => 'nullable',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;

            $videoCheck = Video::where(['id' => $request->video_id])->first();
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }
            $subscriptionCheck = Subscription::where(['user_id' => $user, 'subcription_for' => 1])->orderBy('id', 'desc')->first();

            $videoPlayStatusDetail = VideoPlayStatus::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id, 'subcription_id' => $subscriptionCheck->id])->first();
            dd($videoPlayStatusDetail);

            //Add on watch time
            if (@$videoPlayStatusDetail) {
                $watchTime = $videoPlayStatusDetail->watch_time + $request->watch_time;

            } else {

                $watchTime = $request->watch_time;
            }

            //video paused or complete 1=>paused,2=>complete
            if (@$videoPlayStatusDetail->watch_time >= $videoCheck->video_total_time || $watchTime >= $videoCheck->video_total_time) {

                $video_status = 2; // video fully watched
            } else {

                $video_status = 1; // video not fully watched yet

            }
            $data = VideoPlayStatus::updateOrCreate(
                [
                    'video_id' => $request->video_id,
                    'subcription_id' => $subscriptionCheck->id,
                    'user_id' => $user,
                ],
                [
                    'subcription_id' => $subscriptionCheck->id,
                    'video_id' => $request->video_id,
                    'is_progress_video' => $request->is_progress_video,
                    'watch_time' => $watchTime,
                    'status' => $video_status, //1=>paused 2=>completed
                    'user_id' => $user,
                ]
            );

            return res_success('Success', $data);

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/video-download",
     *     tags={"Videos"},
     *     summary="Download Video",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="videoDownload",
     *  @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
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
    //Download Video
    public function videoDownload(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $videoCheck = Video::where(['id' => $request->video_id])->first();
            if (empty($videoCheck)) {
                return res_failed1('Video not found.');
            }
            $videos = $videoCheck->video_url;
            $vdoCipherVedioDetail = $this->vdoCipherVedioDetailOffline($videos);
            $data = VideoDownload::updateOrCreate(
                [
                    'video_id' => $request->video_id,
                    'user_id' => $user,
                ],
                [
                    'video_id' => $request->video_id,
                    'user_id' => $user,
                ]
            );
            $data['video_url'] = $videoCheck->video_url;
            $topic = Topic::where('id', $videoCheck->topic_id)->first();
            $data['video_title'] = @$videoCheck->video_title;
            $teachers = Teacher::where('id', $videoCheck->teacher_id)->first();
            //echo "<pre>"; print_r($videoCheck->teacher_id); die;
            $data['teacher_name'] = @$teachers->teacher_name;

            $data = [
                "video_data" => $data,
                "video_deatil" => $vdoCipherVedioDetail,
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
     *     path="/api/video-download-list",
     *     tags={"Videos"},
     *     summary="Download Video",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="videoDownloadList",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * ),

     *
     * )
     */
    //Download Video
    public function videoDownloadList(Request $request)
    {
        try {

            $user = auth('api_user')->user()->id;

            $videosList = VideoDownload::where(['user_id' => $user])->pluck('video_id')->toArray();
            //dd($videosList);

            $videoCheck = Video::whereIn('videos.id', $videosList)->select('videos.*', 'subjects.subject_name', 'subjects.id', 'videos.video_title as video_title', 'teachers.teacher_name')
                ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
                ->join('teachers', 'videos.teacher_id', '=', 'teachers.id')
                ->join('topics', 'videos.topic_id', '=', 'topics.id')
                ->get();
            $videoArray = [];
            foreach ($videoCheck as $video) {

                $videos = $video;
                $videos->vdoCipherVedioDetail = $this->vdoCipherVedioDetail($videos->video_url);
                $videoArray[] = $videos;
            }

            $data = [
                //  "video_data"=>$data,
                "video_deatil" => $videoArray,
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
     *     path="/api/countinue-video-list",
     *     tags={"Videos"},
     *     summary="Countinue Video",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="CountinueVideoList",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * ),

     *
     * )
     */
    //Countinue video list

    public function CountinueVideoList()
    {
        try {
            $user = auth('api_user')->user()->id;
            $awsUrl = env('AWS_URL');

            // Get the latest subscription
            $latestSubscription = Subscription::where('user_id', $user)
                ->orderBy('id', 'desc')
                ->first();
//dd($latestSubscription->id);

            // Check if the latest subscription is available
            if (!$latestSubscription) {
                return res_failed('No subscription found for the user.');
            }

            // Get the video IDs for continued playback
            $countinueVideos = VideoPlayStatus::where(['user_id' => $user, 'status' => 1])->pluck('video_id')->toArray();

            // Get the video details for the continued playback videos
            $video = Video::whereIn('videos.id', $countinueVideos)
                ->select('videos.*', 'subjects.subject_name', 'subjects.id as subject_id', 'videos.video_title as video_title', 'teachers.teacher_name', 'video_play_statuses.is_progress_video', DB::raw("CONCAT('" . asset('public/video/thumbnail/') . '/' . "', videos.video_thumbnail) AS video_thumbnail"))
                ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
                ->join('teachers', 'videos.teacher_id', '=', 'teachers.id')
                ->join('topics', 'videos.topic_id', '=', 'topics.id')
                ->join('video_play_statuses', 'videos.id', '=', 'video_play_statuses.video_id')
                ->where('video_play_statuses.user_id', $user)
                ->where('video_play_statuses.subcription_id', $latestSubscription->id)
                ->orderBy('video_play_statuses.id', 'desc')
                ->get();

            // Check if any videos are found
            if ($video->isEmpty()) {
                return res_failed('No videos found for continued playback.');
            }

            // Handle the case where 'is_progress_video' is null
            foreach ($video as $videos) {
                if ($videos->is_progress_video === null) {
                    // Set a default value for 'is_progress_video'
                    $videos->is_progress_video = "";
                }
            }

            // Prepare the response data
            $data = [
                'countinue_video' => $video,
            ];

            // Return success response with data
            return res_success('Success', $data);
        } catch (Exception $e) {
            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Delete(
     *     path="/api/delete-downloaded-video",
     *     tags={"Videos"},
     *     summary="Delete downloaded videos",
     *     description="Delete downloaded videos",
     *     security={{"bearerAuth":{}}},
     *     operationId="deleteDownloadedvideo",
     *  @OA\Parameter(
     *         name="video_id",
     *         in="query",
     *         description="Enter Video id",
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
    public function deleteDownloadedvideo(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'video_id' => 'required',
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $discardCustomQB = VideoDownload::where(['video_id' => $request->video_id, 'user_id' => auth('api_user')->user()->id])->delete();
            return res_success('Video deleted sccussfully!');

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
}
