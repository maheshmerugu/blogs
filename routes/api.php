<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckApiToken;
use App\Http\Middleware\CheckSubscription;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NewsConroller;
use App\Http\Controllers\Api\McqController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\PlanSubscriptionController;
use App\Http\Controllers\Api\CMSController;
use App\Http\Controllers\Api\QuestionBankController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\ScheduleClassController;
use App\Http\Middleware\ApiResponseTimeMiddleware;
use App\Http\Controllers\Api\ZoomController;
use App\Http\Middleware\CheckAccessQB;
use App\Http\Middleware\CheckDevice;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware([ApiResponseTimeMiddleware::class])->group(function () {

Route::controller(UserController::class)->group(
        function () {
                Route::post('/sign-up', 'sign_up');
                Route::post('/verify-sign-up', 'verifySignUp');
                Route::post('/verify-otp', 'verifyOtp');
                Route::post('/resend-otp', 'resendOTP');
                Route::post('/login', 'login');
                Route::post('/forgot-password', 'forgotPassword');
                Route::put('/reset-password', 'resetPassword');

                /***************** College management ***********************/
                Route::get('/college-list', 'collegeList');
                Route::get('/year-list', 'yearList');
        }
);
 /***************** Content Management ***********************/
        Route::controller(CMSController::class)->group(
                function () {
                        Route::get('/faq-lists', 'faqList');
                        Route::get('/cms-lists', 'cmsList');
                        Route::get('/get-razorpay-key', 'get_razorpay_key');
                         Route::get('/get-contact-details', 'get_contact_details');
                        Route::post('/contact-us', 'contactUs');
                }
        );
        Route::middleware([CheckApiToken::class])->group(function () {
        
        
       Route::post('/streak-count', [BookmarkController::class, 'streakCount']);
       Route::get('/select-question-bank', [QuestionBankController::class, 'selectQuestionBank']);
        Route::get('/select-subject', [QuestionBankController::class, 'selecSubject']);
          Route::get('/select-module', [QuestionBankController::class, 'selecModule']);
            Route::get('/select-subject-original-qb', [QuestionBankController::class, 'selectSubjectForOriginQB']);
              Route::get('/topic-question-count', [QuestionBankController::class, 'topicQuestionCount']);
           
             
               Route::controller(BookmarkController::class)->group(
                function () {
                        Route::get('/sub-bookmarks-datas-list', 'bookmarkDatasnew');
                        Route::get('/bookmarks-datas-list', 'bookmarkDatas');
                        Route::get('/video-watch-hour-list', 'videoWatchHourList');
                        Route::get('/get-qb-bookmark-module', 'getQBBookmarkModule');
                        Route::get('/get-bookmark-question', 'bookmarkQuestions');
                        Route::get('/notes-bookmarks-list', 'notesBookmarkList');
                       // Route::get('/video-watch-hour-list', 'videoWatchHourList');
                        // Route::post('/streak-count', 'streakCount');
                }
        );
      
        Route::controller(UserController::class)->group(
                function () {
                        Route::post('/update-profile', 'updateProfile');
                        Route::delete('/delete-account', 'deleteAccount');
                        Route::post('/change-password', 'changePassword');
                        Route::post('/logout', 'logout');
                         Route::post('/check-device', 'check_device');
                }
        );

        /********************* News ****************************/
        Route::controller(NewsConroller::class)->group(
                function () {
                        Route::get('/home', 'home');
                        Route::get('/news-list', 'newsList');
                        Route::post('/news-like', 'newslikemark');
                        Route::post('/news-view', 'newsView');
                        Route::post('/news-bookmark', 'newsBookmark');
                }
        );
        /***************** Mcq management ***********************/
        Route::controller(McqController::class)->group(
                function () {
                        Route::get('/missed-mcq', 'missedMcq');
                        Route::post('/attempt-mcq', 'attemptMcq');
                }
        );

        /***************** Video management ***********************/
         Route::controller(ScheduleClassController::class)->group(
                                                function () {
                                                       Route::get('sub-ongoing-previous-events', 'ongoingPrevious');
                                                       
                                                }
                                        );
                                        
       
        
        Route::controller(VideoController::class)->group(
                function () {
                        Route::get('/get-subjects', 'getSubject');
                        Route::get('/free-topics', 'getTopicssub');
                        Route::get('/get-sub-teachers', 'getsubTeachers');
                        Route::post('/get-sub-video-detail', 'getSubVideoDetail');
                        Route::post('/verify-sub-otp-video', 'verifyOtpvideo');
                        
                        Route::post('/sub-video-bookmark', 'videoBookmark');
                        Route::post('/sub-video-download', 'videoDownload');
                        Route::post('/sub-video-hand-writting-notes', 'HandWrittingNotes');
                        Route::post('/sub-edit-video-hand-writting-notes', 'EditHandWrittingNotes');
                        Route::get('/sub-countinue-video-list', 'CountinueVideoList');
                        Route::post('/sub-video-play-status', 'subvideoPlayStatus');
                        Route::get('/sub-get-topics', 'getTopics');
                        //Check user subscribed or not
                        Route::middleware([CheckSubscription::class])->group(
                                function () {
                                        Route::get('/get-teachers', 'getTeachers');
                                        Route::get('/get-topics', 'getTopics');

                                        Route::post('/get-video-detail', 'getVideoDetail');
                                        Route::post('/video-bookmark', 'videoBookmark');
                                        Route::post('/video-rating', 'VideoRating');
                                        Route::post('/video-hand-writting-notes', 'HandWrittingNotes');
                                        Route::post('/edit-video-hand-writting-notes', 'EditHandWrittingNotes');
                                        Route::post('/video-play-status', 'videoPlayStatus');
                                        Route::post('/video-download', 'videoDownload');
                                        Route::get('/video-download-list', 'videoDownloadList');
                                        Route::post('/verify-otp-video', 'verifyOtpvideo');
                                        Route::get('/countinue-video-list', 'CountinueVideoList');
                                        Route::delete('/delete-downloaded-video', 'deleteDownloadedvideo');
                                        //Schedule Class
                                        Route::controller(ScheduleClassController::class)->group(
                                                function () {
                                                      /* Route::get('schedule-class/get-subjects', 'getSubjectForClasses');*/
                                                        Route::get('ongoing-previous-events', 'ongoingPrevious');
                                                        Route::get('date-filter-events', 'dateFilterEvents')->withoutMiddleware([CheckSubscription::class]);

                                                }
                                        );
                                         //Bookmark
/*        Route::controller(BookmarkController::class)->group(
                function () {
                       // Route::get('/bookmarks-datas-list', 'bookmarkDatas');
                        Route::get('/video-watch-hour-list', 'videoWatchHourList');
                        Route::get('/get-qb-bookmark-module', 'getQBBookmarkModule');
                        Route::get('/get-bookmark-question', 'bookmarkQuestions');
                        Route::get('/notes-bookmarks-list', 'notesBookmarkList');
                       // Route::get('/video-watch-hour-list', 'videoWatchHourList');
                        // Route::post('/streak-count', 'streakCount');
                }
        );*/

                //Question Bank              
        

                                }
                        );
                }
        );
        Route::controller(QuestionBankController::class)->group(
                function () {
        Route::get('/sub-original-qb-list', 'originalQbQuestionList');
        Route::post('/sub-questions-attempt', 'questionAttempt');
        Route::get('/sub-original-qb-result', 'result');
        Route::delete('/sub-discard-question-bank', 'discardQuestionBank');
        Route::post('/sub-questions-unattempt', 'questionUnAttempt');
         Route::post('/sub-qb-bookmark', 'qbBookmark');
                }
        );
         Route::middleware([CheckAccessQB::class])->group(
                                function () { 
        Route::controller(QuestionBankController::class)->group(
                function () {
                        /********************Custom Question Bank *********************/
                        // Route::get('/select-question-bank', 'selectQuestionBank');
                      //  Route::get('/select-year-level-type', 'selectYearLevelType');
                        // Route::get('/select-subject', 'selecSubject');
                        // Route::get('/select-module', 'selecModule');
                        Route::post('/create-custom-qb', 'createCustomQb');
                        Route::get('/custom-questions-list', 'customQuestionList');
                        Route::post('/questions-attempt', 'questionAttempt');
                        Route::post('/questions-unattempt', 'questionUnAttempt');
                        Route::get('/result-custom-question-bank', 'customQuestionBankResult');
                        Route::delete('/discard-custom-question-bank', 'discardCustomQuestionBank');

                        /********************Question Bank *********************/
                        //  Route::get('/select-subject-original-qb', 'selectSubjectForOriginQB');
                        // Route::get('/topic-question-count', 'topicQuestionCount');
                        Route::get('/original-qb-list', 'originalQbQuestionList');
                        Route::get('/original-qb-result', 'result');
                        Route::delete('/discard-question-bank', 'discardQuestionBank');
                        // Route::get('/user-attempted-questions', [QuestionBankController::class, 'userAttemptedQuestion']);
        
                        Route::post('/qb-bookmark', 'qbBookmark');
                }
        );
                                }
        );
          Route::post('/select-year-level-type', [QuestionBankController::class,'selectYearLevelType']);
           Route::get('/schedule-class/get-subjects', [ScheduleClassController::class,'getSubjectForClasses']);

        /***************** Plan Management ***********************/
        Route::controller(PlanSubscriptionController::class)->group(
                function () {
                        Route::get('/plan-lists', 'planList');
                        Route::post('/validate-coupon', 'validateCoupon');
                        Route::post('/order-generate', 'orderGenerate');
                        Route::post('/subscribe-plan', 'subscribePlan');
                }
        );
       


       
        //Reminder
        Route::controller(ReminderController::class)->group(
                function () {
                        Route::post('/set-reminder', 'setReminder');
                        Route::get('/list-reminder', 'listReminder');
                        Route::post('/send-notification', 'sendNotification');
                        Route::post('/edit-reminder', 'editReminder');
                        Route::post('/status-push-notification', 'statusPushNotification');
                        Route::delete('/delete-reminder', 'deleteReminder');
                        Route::get('/notification', 'notification');
                }
        );

});
// });

// Route to generate JWT token
Route::post('/generate-jwt-token', [ZoomController::class, 'generateJwtToken']);
