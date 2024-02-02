<?php
class VideoController extends Controller
{
    public function getTopicssub(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                // 'year_id' => 'required',
                'subject_id' => 'required',
                'teacher_id' => 'required',
                'video_status' => 'nullable|in:0,1,2,3',
                "search" => 'nullable|string|max:25',
            ]);

            if ($data->fails()) {
                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (object) [];

                return response()->json($result);
            }

            $user = auth('api_user')->user()->id;

            $user = auth('api_user')->user()->id;

            $user_year_id = auth('api_user')->user()->year_id;

            // $subject = Subject::where(['id' => $request->subject_id])
            //     ->select('id', 'year_id', 'subject_name')
            //     ->withCount('video')
            //     ->withSum('video', 'video_total_time')
            //     ->first();
            $teacherId = $request->teacher_id;

            //if user subscribed
            $subscriptionCheck = Subscription::where(['user_id' => $user, 'subcription_for' => 1])->orderBy('id', 'desc')->first();
            if ($subscriptionCheck) {

                $subject = Subject::where('id', $request->subject_id)
                    ->where('year_id', $request->year_id)

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
                // echo "<pre>"; print_r($subject); die;

                $subject->video_count = intval($subject->video_count);
                $subject->teacher_name = Teacher::where(['id' => $request->teacher_id])->pluck('teacher_name')->first();

                $module = Module::where([
                    'subject_id' => $request->subject_id,
                    'year_id' => $request->year_id,
                    'teacher_id' => $request->teacher_id,
                ])->with([
                    'topics' => function ($q) use ($request, $user) {
                        $q->with(['video' => function ($qs) use ($request, $user) {
                            if ($request->video_status == 1) {

                                $qs->whereIn('id', function ($query) use ($user) {
                                    $query->select('video_id')
                                        ->from('video_play_statuses')
                                        ->where(['user_id' => $user, 'status' => 1]);
                                });

                            } elseif ($request->video_status == 2) {
                                $qs->whereIn('id', function ($query) use ($user) {
                                    $query->select('video_id')
                                        ->from('video_play_statuses')
                                        ->where(['user_id' => $user, 'status' => 2]);
                                });
                            } elseif ($request->video_status == 3) {
                                $qs->whereNotIn('id', function ($query) use ($user) {
                                    $query->select('video_id')
                                        ->from('video_play_statuses')
                                        ->where(['user_id' => $user]);
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

                // $module = $module->get()->map(function ($module) {
                //     $module->topics = $module->topics->map(function ($topic) {
                //         $topic->video = $topic->video->map(function ($video) {
                //             $video->subscribed_video = 1;
                //             return $video;
                //         });

                //         return $topic;
                //     });

                //     $module->topics = $module->topics->filter(function ($topic) {
                //         return $topic->video->isNotEmpty();
                //     });

                //     return $module;
                // });

                $module = $module->get()->filter(function ($module) {
                    $module->topics = $module->topics->filter(function ($topic) {
                        return $topic->video->isNotEmpty();
                    });

                    $module->topics = $module->topics->map(function ($topic) {
                        $topic->video = $topic->video->map(function ($video) {

                            $user_year_id = auth('api_user')->user()->year_id;

                            if ($user_year_id == $video->year_id || $video->video_type == 1) {
                                $video->subscribed_video = 1;

                            } else {
                                $video->subscribed_video = 0;

                            }

//info("123456");
                            return $video;
                        });
                        return $topic;
                    });

                    return $module->topics->isNotEmpty();
                });

                $module = $module->values();
                $data = [
                    'subject' => $subject,
                    'module' => $module,
                ];

                return res_success('Success', $data);

            } else {
                //writen for unsubsrcibed user

//info("123456abc");
                $user = auth('api_user')->user()->id;

                $teacherId = $request->teacher_id;
                $subject = Subject::where('id', $request->subject_id)
                    ->where('year_id', $request->year_id)

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

                // dd($subject);
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
                    'topics' => function ($q) use ($request, $user) {
                        $q->with(['video' => function ($qs) use ($request, $user) {
                            if ($request->video_status == 1) {
                                $qs->whereIn('id', function ($query) use ($user) {
                                    $query->select('video_id')
                                        ->from('video_play_statuses')
                                        ->where(['user_id' => $user]);
                                });
                                // } elseif ($request->video_status == 2) {
                                //     $qs->whereIn('id', function ($query) use ($user) {
                                //         $query->select('video_id')
                                //             ->from('video_play_statuses')
                                //             ->where(['user_id' => $user, 'status' => 2]);
                                //     });
                                // } elseif ($request->video_status == 3) {
                                //     $qs->whereNotIn('id', function ($query) use ($user) {
                                //         $query->select('video_id')
                                //             ->from('video_play_statuses')
                                //             ->where(['user_id' => $user]);
                                //     });
                            } else {
                                $qs->where('status', 1);
                            }
                        }]);
                        $q->whereHas('video', function ($query) {
                            $query->where('status', 1);
                        });
                    },
                ]);

//   $module = $module->get()->map(function ($module) {
//                     $module->topics = $module->topics->map(function ($topic) {
//                         $topic->video = $topic->video->map(function ($video) {

//                             if($video->video_type == '1'){
//                                 $video->subscribed_video=1;
//                             }else{
//                               $video->subscribed_video=0;
//                             }

//                             return $video;
//                         });

//                         return $topic;
//                     });

//                     $module->topics = $module->topics->filter(function ($topic) {
//                         return $topic->video->isNotEmpty();
//                     });

//                     return $module;
//                 });

                //  $module = $module->get()->filter(function ($module) {
                //     $module->topics = $module->topics->filter(function ($topic) {
                //         return $topic->video->isNotEmpty();
                //     });
                //     return $module->topics->isNotEmpty();
                // });

//     $module = $module->get()->map(function ($module) {
//     $module->topics = $module->topics->map(function ($topic) {
//         $topic->video = $topic->video->map(function ($video) {
//             if ($video->video_type == '1') {
//                 $video->subscribed_video = 1;
//             } else {
//                 $video->subscribed_video = 0;
//             }
//             return $video;
//         });

//         return $topic;
//     });

//     $module->topics = $module->topics->filter(function ($topic) {
//         return $topic->video->isNotEmpty();
//     });

//     return $module;
// });

                $module = $module->get()->filter(function ($module) {
                    $module->topics = $module->topics->filter(function ($topic) {
                        return $topic->video->isNotEmpty();
                    });

                    $module->topics = $module->topics->map(function ($topic) {
                        $topic->video = $topic->video->map(function ($video) {

                            // info($video);
                            if ($video->video_type == 1) {
                                $video->subscribed_video = 1;
                            } else if ($video->video_type == 0) {
                                $video->subscribed_video = 0;

                            }
                            return $video;
                        });
                        return $topic;
                    });

                    return $module->topics->isNotEmpty();
                });

// dd($module);

                //  $module = $module->get()->filter(function ($module) {
                //     $module->topics = $module->topics->filter(function ($topic) {
                //         return $topic->video->isNotEmpty();
                //     });
                //     return $module->topics->isNotEmpty();
                // });

                $module = $module->values();
                $data = [
                    'subject' => $subject,
                    'module' => $module,
                ];

                return res_success('Success', $data);

            }

        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
}
