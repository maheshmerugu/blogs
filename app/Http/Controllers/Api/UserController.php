<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\Year;
use App\Models\College;
use App\Models\DummyUsers;
use Exception;
use App\Helpers\Helper;
use App\Helpers\EmailHelper; 
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use DB;



class UserController extends Controller
{

    /**
    *  @OA\Post(
    *     path="/api/sign-up",
    *     tags={"User"},
    *     summary="User Signup",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="sign_up",
    *     @OA\Parameter(
    *         name="name",
    *         in="query",
    *         description="Enter user name",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="Sunil Tiwari",
    *            
    *         )
    *     ),
    * @OA\Parameter(
    *         name="mobile",
    *         in="query",
    *         description="Enter user mobile number",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="7007979552",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="college_id",
    *         in="query",
    *         description="Enter college id",
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
    *         name="year_id",
    *         in="query",
    *         description="Enter year",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="2022",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="Enter user email",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="tsunil870@gmail.com",
    *             
    *         )
    *     ),
    *   @OA\Parameter(
    *         name="password",
    *         in="query",
    *         description="Enter password",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="12345678",
    *            
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="device_id",
    *         in="query",
    *         description="Enter device id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *            
    *            
    *            
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="device_type",
    *         in="query",
    *         description="Enter device type",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *            
    *            
    *            
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="fcm_token",
    *         in="query",
    *         description="Enter fcm token",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
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
    
    * 
    * )
    */

    public function sign_up(Request $request)
    {

        try {
            $data = Validator::make($request->all(), [
                //  'country_code' => 'required',
                'mobile' => 'required',
                'email' => 'required',
                'name' => 'required|string|max:255',
                'password' => 'required|min:6',
                //  'confirm_password' => 'required|same:password',
                'year_id' => 'required',
                'college_id' => 'required',


            ]);
            // dd($data);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            
            if($request->college_id == 0){
                 $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = "The college field is required.";
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            if($request->year_id == 0){
                 $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = "The year field is required.";
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $data = $request->all();

            $mobileCheck = User::where(['mobile_number' => $request->mobile, 'email_verified' => 1])->first();
            $emailCheck = User::where(['email' => $request->email, 'email_verified' => 1])->first();

            if ($mobileCheck) {
                return res_failed1('Mobile number already registered.');
            } elseif ($emailCheck) {
                return res_failed1('Email already registered.');

            }

             $mobile_otp =   Helper::send_mobile_otp($request->mobile);
            
           // $mobile_otp = 1234;
                    $email_otp = rand(1231, 7879);
        //             $body = 'Your OTP is : ' . $email_otp . ' Please do not share with anybody.';
        //             $details = [
        //                 'name' => 'Mail from Auricle',
        //                 'body' => $body
        //             ];
        //           try{
        //              Mail::to($request->email)->send(new SendEmail($details));
                      
        //           } catch (\Exception $e) {
        //     // Error occurred while sending the email
        //     echo "Error sending email: " . $e->getMessage(); die;
        // }
           
                           //start temprory mail code
                        /*   $email_otp = rand(1231, 7879);
                $body = 'Your OTP is: ' . $email_otp . ' Please do not share it with anybody.';
                $details = [
                    'name' => 'Auricle',
                    'body' => $body
                ];
                
                $to = $request->email;
                $subject = 'Mail from Auricle';
                $message = '
                <html>
                <head>
                    <title>' . $details['name'] . '</title>
                </head>
                <body>
                    <h1>' . $details['name'] . '</h1>
                    <p>' . $details['body'] . '</p>
                </body>
                </html>
                ';
                
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: Auricle'; // Replace 'your_username' with the appropriate email account username
                
                mail($to, $subject, $message, $headers);*/
                
                //end mail code
           
              //dd($details);
            $user = DummyUsers::create([
                'name' => $data['name'],
                'mobile_number' => $data['mobile'],
                //  'country_code' => $data['country_code'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'year_id' => $data['year_id'],
                'college_id' => $data['college_id'],
                'mobile_otp' => $mobile_otp,
                'email_otp' => $email_otp,
                'device_id' => $data['device_id'] ?? '',
                'new_device_id' => $data['device_id'] ?? '',
                'device_type' => $data['device_type'] ?? '',
                'fcm_token' => @$data['fcm_token'],
            ]);
             $emailResponse = EmailHelper::sendEmailWithCurlForSignup(
                    $request->email,
                    $request->name,
                    $email_otp,
                    "template_10_10_2023_16_10" // Replace with your template ID
                );

            return res_success('Success', $request->all());
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }

    }

    /**
    *  @OA\Post(
    *     path="/api/verify-otp",
    *     tags={"User"},
    *     summary="User Verify",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="verifyOtp",
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="Enter user email",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="tsunil870@gmail.com",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="otp",
    *         in="query",
    *         description="Enter otp",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1234",
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
    public function verifyOtp(Request $request)
    {

        try {

            $validation = Validator::make($request->all(), [
                'email' => 'nullable',

            ]);

            if ($validation->fails()) {

                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $user = User::where('email', $request->email)->orWhere('mobile_number', $request->email)->orderBy('id','desc')->first();
            //dd($user);
            if (isset($user)) {
                if (($user->email_otp == $request->otp) || ($user->mobile_otp == $request->otp) ) {
                     $userVerify = User::where('email', $request->email)->orWhere('mobile_number', $request->email)->first();
                    $userVerify->email_verified = 1;
                    $userVerify->save();
                    $userVerify->token = $userVerify->createToken('auth_token')->accessToken;
                    return res_success('OTP verified successfully', $userVerify);
                }
                    return res_failed('Otp is not valid!');

            } else {

                return res_failed('Invalid user!');
            }
        } catch (Exception $e) {

            $exception['status'] = config('messages.http_codes.server');
            $exception['message'] = config('messages.error_messages.server_error');
            $exception['data'] = [];
            return $e->getMessage();
        }
    }
     /**
    *  @OA\Post(
    *     path="/api/verify-sign-up",
    *     tags={"User"},
    *     summary="Signup  Verify",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="verifySignUp",
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="Enter user email",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="tsunil870@gmail.com",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="email_otp",
    *         in="query",
    *         description="Enter email otp",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1234",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="mobile_number",
    *         in="query",
    *         description="Enter user mobile number",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="917007979552",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="mobile_otp",
    *         in="query",
    *         description="Enter mobile otp",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1234",
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
     public function verifySignUp(Request $request)
     {
        try{
            $validation = Validator::make($request->all(), [
                'email' => 'required',
                'mobile_number' => 'required',

            ]);

            if ($validation->fails()) {

                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = DummyUsers::where('email', $request->email)->orWhere('mobile_number', $request->email)->orderBy('id','desc')->first();

               if (isset($user)) {
              
                if ($user->email_otp != $request->email_otp) {
                    return res_failed('Email otp is not valid!');
                }
                if ($user->mobile_otp != $request->mobile_otp ){
                return res_failed('Mobile otp is not valid');
                }

                $user->email_verified = 1;
                $user->save();
                $mobileCheck = User::where(['mobile_number' => $request->email])->first();

                $emailCheck = User::where(['email' => $request->email])->first();

                if ($mobileCheck || $emailCheck) {
                    $userVerify = User::where('email', $request->email)->orWhere('mobile_number', $request->email)->first();

                    $userVerify->email_verified = 1;
                    $userVerify->save();
                    $userVerify->token = $userVerify->createToken('auth_token')->accessToken;
                    return res_success('OTP verified successfully', $userVerify);
                } else {

                    $data = User::create([
                        'name' => $user->name,
                        'mobile_number' => $user->mobile_number,
                        'email' => $user->email,
                        'password' => $user->password,
                        'year_id' => $user->year_id,
                        'college_id' => $user->college_id,
                        'mobile_otp' => $user->mobile_otp,
                        'email_otp' => $user->email_otp,
                        'device_id' => $user->device_id,
                        'device_type' => $user->device_type,
                        'email_verified' => $user->email_verified,
                        'fcm_token' => $user->fcm_token,
                        'new_device_id' => $request->device_id,

                    ]);
                    $user_id = User::latest('id')->first();
                    DB::table('user_log_history')->insert(['user_id' => $user_id->id, 'device_id' => $request->device_id, 'fcm_token' => $user->fcm_token, 'device_name' => $request->device_type,'device_model' => $request->device_model, 'login_time' => date('Y-m-d H:i:s')]);
                    $data->token = $data->createToken('auth_token')->accessToken;
                }
                return res_success('Success', $data);

            }else {

                return res_failed('Invalid user!');
            }
        }catch (Exception $e) {

            $exception['status'] = config('messages.http_codes.server');
            $exception['message'] = config('messages.error_messages.server_error');
            $exception['data'] = [];
            return $e->getMessage();
     }
 }
    /**
    *  @OA\Post(
    *     path="/api/resend-otp",
    *     tags={"User"},
    *     summary="Resend Otp",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="resendOtp",
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="Enter user email",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="tsunil870@gmail.com",
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
    public function resendOTP(Request $request)
    {

        try {

            $validation = Validator::make($request->all(), [

                'email' => 'required',
            ]);

            if ($validation->fails()) {

                return res_failed('Email or mobile number is required!');
            }


            $email = $request->input('email');
            $checkUser = DummyUsers::where(['email' => $email])->orWhere('mobile_number', $email)->orderBy('id','desc')->first();

            if (isset($checkUser)) {
                if ($checkUser->mobile_number == $email) {
                    $mobile_otp =   Helper::send_mobile_otp($email);
                    //$mobile_otp = 1234;
                    DummyUsers::where('id', $checkUser->id)->update(array('mobile_otp' => $mobile_otp, 'email_verified' => 0));
                } else {
                     $email_otp = rand(1231, 7879);
                    //$email_otp = 1234;

                    DummyUsers::where('id', $checkUser->id)->update(array('email_otp' => $email_otp, 'email_verified' => 0));

                    /*$body = 'Your OTP is : ' . $email_otp . ' Please do not share with anybody.';
                    $details = [
                        'name' => 'Mail from Auricle',
                        'body' => $body
                    ];*/
                   
                     $emailResponse = EmailHelper::sendEmailWithCurlForSignup(
                        $request->email,
                        $request->name,
                        $email_otp,
                        "template_10_10_2023_16_10" // Replace with your template ID
                    );
                    // $email_otp = rand(1231, 7879);
                  /*  $body = 'Your OTP is: ' . $email_otp . ' Please do not share it with anybody.';
                    $details = [
                        'name' => 'Auricle',
                        'body' => $body
                    ];
                    
                    $to = $request->email;
                    $subject = 'Mail from Auricle';
                    $message = '
                    <html>
                    <head>
                        <title>' . $details['name'] . '</title>
                    </head>
                    <body>
                        <h1>' . $details['name'] . '</h1>
                        <p>' . $details['body'] . '</p>
                    </body>
                    </html>
                    ';
                    
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: Auricle'; // Replace 'your_username' with the appropriate email account username
                    
                    mail($to, $subject, $message, $headers);*/
                    
                    //end mail code
                    //   Mail::to($request->email)->send(new SendEmail($details));
                }


                return res_success('Otp sent successfully.');
            } else {
                return res_failed('Email or mobile number does not exist.');
            }

        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Post(
     *     path="/api/forgot-password",
     *     tags={"User"},
     *     summary="Forgot Password",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="forgotPassword",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter email ",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
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

   public function forgotPassword(Request $request)
    {

        try {


            if ($request->email) {

            } else {
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = "At least one field is required";
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            // $mobile = $request->input('mobile');
            $email = $request->input('email');

            $checkUser = User::where('email', $email)->orWhere('mobile_number', $email)->first();

            if (isset($checkUser) && $checkUser != null) {
                $mobile_otp = Helper::send_mobile_otp($email);
                //$otp = Helper::generateOtp();
                $otp = rand(1231, 7879);

                User::where('id', $checkUser->id)->update(array('email_otp' => $otp, 'mobile_otp' => $mobile_otp, 'email_verified' => 0));

                
                /* $body = 'Your OTP is : ' . $otp . ' Please do not share with anybody.';
                $details = [
                'name' => 'Mail from Auricle',
                'body' => $body
                ];*/

                //  Mail::to($request->email)->send(new SendEmail($details));
                //start temprory mail code

            //     $body = 'Your OTP is: ' . $otp . ' Please do not share it with anybody.';
            //     $details = [
            //         'name' => 'Auricle',
            //         'body' => $body
            //     ];

            //     $to = $request->email;
            //     $subject = 'Mail from Auricle';
            //     $message = '
            // <html>
            // <head>
            //     <title>' . $details['name'] . '</title>
            // </head>
            // <body>
            //     <h1>' . $details['name'] . '</h1>
            //     <p>' . $details['body'] . '</p>
            // </body>
            // </html>
            // ';

            //     $headers = "MIME-Version: 1.0" . "\r\n";
            //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            //     $headers .= 'From: Auricle'; // Replace 'your_username' with the appropriate email account username

            //     mail($to, $subject, $message, $headers);
             $emailResponse = EmailHelper::sendEmailWithCurlForSignup(
                    $request->email,
                    $request->name,
                    $otp,
                    "template_10_10_2023_16_10" // Replace with your template ID
                );
                //end mail code
                return res_success('Success', ['email_otp' => $otp, 'mobile_otp' => $mobile_otp]);

            } elseif ($email) {

                return res_failed('Invalid mobile or email. Please try again.');
            }
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/login",
     *     tags={"User"},
     *     summary="Login",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="login",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter email or mobile",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *              default="tsunil870@gmail.com",
     *           
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Enter password",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *             default="12345678",
     *           
     *            
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="device_id",
     *         in="query",
     *         description="Enter device id",
     *         required=true,
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
     *     @OA\Parameter(
     *         name="device_type",
     *         in="query",
     *         description="Enter device type",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *             default="ios",
     *            
     *            
     *            
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="fcm_token",
     *         in="query",
     *         description="Enter fcm token",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="string",
     *           
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

    public function login(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [

                "email" => 'required',
                "password" => 'required',
            ]);

            if ($validation->fails()) {

                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $checkUser = User::where(['email' => $request->email])->orWhere('mobile_number', $request->email)->first();

            if (!$checkUser) {

                return res_failed('User does not exist.');
            }
            if ($checkUser->status == 1) {
                $user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
                if (!$user) {
                    $user = Auth::attempt(['mobile_number' => $request->email, 'password' => $request->password]);
                    if (!$user) {
                        return res_failed('Invalid credentials. Please try again.');

                    }

                }
                $user = Auth::user();
                if ($user->email_verified == 1) {
                    $id = $user->id;
                    $user->update([
                        'device_type' => $request->device_type,
                        'device_token' => $request->device_token,
                        'fcm_token' => $request->fcm_token,
                    ]);
                    /* $awsUrl = env('AWS_URL').'auricle/user/profile_img/';
                 $user->profile_img = $awsUrl.$user->profile_img;*/
                  if($user->profile_img){
                         $userProfileImage = asset('images/user_image/').'/'  . $user->profile_img;
                    }else{
                        $userProfileImage= "";
                    }
                    $user->profile_img = $userProfileImage;
                    $user->token = $user->createToken('Auricle')->accessToken;
                     $user->college_name = College::where('id',$user->college_id)->pluck('college_name')->first();
                     $user->device_id = DB::table('user_log_history')->where('device_id',$request->device_id)->where('user_id',$user->id)->count();
                     if($user->login_out >= 3){
                       $get_value = User::where(['email' => $request->email])->orWhere('mobile_number', $request->email)->update(['status'=>'0']);
                       return res_success('Your account has been blocked.Please contact to administrator');
                     }elseif(empty($user->device_id) || $user->login_out == 0){
                         User::where(['email' => $request->email])->orWhere('mobile_number', $request->email)->update(['new_device_id' => $request->device_id]);
                         DB::table('user_log_history')->insert(['user_id' => $user->id, 'device_id' => $request->device_id, 'fcm_token' => $request->fcm_token, 'device_name' => $request->device_type, 'device_model' => $request->device_model, 'login_time' => date('Y-m-d H:i:s')]);
                         $votes = 1;
                        $get_value = User::where(['email' => $request->email])->orWhere('mobile_number', $request->email)->update(['login_out' => User::raw('login_out + '.$votes)]);
                        return res_success('Success', $user);
                     }else{
                         return res_success('Success', $user);
                     }
                   
                } else {
                    return res_success('Please verify your account.');
                }
            } else {
                return res_success('Your account has been blocked.Please contact to administrator');

            }


        } catch (Exception $e) {
        return $e->getMessage(); die;
            return res_catch('Something went wrong!');
        }
    }


    /******************* College Management  *****************************/
    /**
    *  @OA\Get(
    *     path="/api/college-list",
    *     tags={"College Management"},
    *     summary="College management data",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="collegeList",
    *  *  @OA\Parameter(
    *         name="search",
    *         in="query",
    *         description="Search College",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="BBD",
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
    public function collegeList(Request $request)
    {

        try {

            $data['college'] = College::where('college_name', 'like', '%' . $request->search . '%')->get();
            return res_success('Success', $data);

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }
    /******************* Year Management  *****************************/
    /**
    *  @OA\Get(
    *     path="/api/year-list",
    *     tags={"College Management"},
    *     summary="College management ",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="yearList",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function yearList()
    {
        try {

            $data['year'] = Year::all();
            return res_success('Success', $data);

        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }

    /**
     *  @OA\Put(
     *     path="/api/reset-password",
     *     tags={"User"},
     *     summary="Reset Password",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="resetPassword",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter email ",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *              type="string",
     *             default="tsunil870@gmail.com",
     *           
     *            
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         description="Enter mobile number",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer"
     *           
     *            
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Enter password",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *            
     *             type="integer"
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
    public function resetPassword(Request $request)
    {
        try {


            if ($request->email || $request->mobile) {

            } else {
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = "At least one field is required";
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $mobile = $request->input('mobile');
            $email = $request->input('email');

            $checkUser = User::where(['email' => $request->email])->orWhere('mobile_number', $request->mobile)->first();
            // return $request->password; die;
            if (!empty($request->password)) {
                if (isset($checkUser) && $checkUser != null) {

                    User::where('id', $checkUser->id)->update(array('password' => bcrypt($request->password)));

                    return res_success('Password has been reset successfully done.');

                } elseif ($mobile) {

                    return res_failed('Mobile number does not exist.');

                } elseif ($email) {

                    return res_failed('Email does not exist.');
                }
            } else {

                return res_failed('Please enter the password.');
            }
        } catch (Exception $e) {

            return res_catch('Something went wrong!');
        }
    }

    /**
    *  @OA\Post(
    *     path="/api/update-profile",
    *     tags={"User"},
    *     summary="User Signup",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="updateProfile",
    * security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="name",
    *         in="query",
    *         description="Enter user name",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="Sunil Tiwari",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="college_id",
    *         in="query",
    *         description="Enter college id",
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
    *         name="year_id",
    *         in="query",
    *         description="Enter year",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="2",
    *            
    *         )
    *     ), 
    *        @OA\RequestBody(
    *         required=false,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     description="Image to upload",
    *                     property="profile_img",
    *                     type="file",
    *                ),
    *             )
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

   public function updateProfile(Request $request)
{
    try {
        // Validate the incoming data
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'year_id' => 'nullable|integer',
            'college_id' => 'nullable|integer',
            'profile_img' => 'nullable|image', // Assuming this is an image file
        ]);

        // Get the authenticated user
        $user = auth('api_user')->user();

        // Check if a profile image is uploaded and store it in S3
        /*if ($request->hasFile('profile_img')) {
            $data['profile_img'] = Helper::uploadFileToS3($request->file('profile_img'), 'auricle/user/profile_img/');
        }*/
        if ($user->profile_img) {
                $oldImagePath = public_path('images/user_image/' . $user->profile_img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        if ($request->hasfile('profile_img')) {
                $profileImage = $request->file('profile_img');
                $userImageName = $profileImage->getClientOriginalName();
                $profileImage->move(public_path('images/user_image'), $userImageName);
                $data['profile_img']  = $userImageName;
            }

        // Update the user's profile with the validated data
        User::where('id', $user->id)->update($data);

        // Fetch the updated user data
        $user = User::where('id', $user->id)->first();

        // Build the response data
        $responseData = [
            'name' => $user->name,
            'college_id' => $user->college_id,
             'profile_img' => $user->profile_img ? asset('images/user_image/').'/'.$user->profile_img : "",
        ];

        // Fetch and add the college name to the response data
        $collegeName = College::where('id', $user->college_id)->pluck('college_name')->first();
        $responseData['college_name'] = $collegeName;

        // Return the success response with the updated profile data
        return res_success('Success', $responseData);
    } catch (ValidationException $e) {
        // Handle validation errors
        return res_failed1($e->getMessage());
    }
}

    /**
    *  @OA\Post(
    *     path="/api/logout",
    *     tags={"User"},
    *     summary="User Logout ",
    *     description="User logout",
    *     security={{"bearerAuth":{}}},
    *     operationId="logout",
    
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function logout(Request $request)
    {

        $logout = auth('api_user')->user()->token()->revoke();
        DB::table('user_log_history')->where(['user_id' => auth('api_user')->user()->id])->update(['logout' => date('Y-m-d H:i:s')]);
        if ($logout) {
            return res_success("Logged out.");
        }

        return res_failed('Invalid mobile number!');
    }


    /**
    *  @OA\Post(
    *     path="/api/change-password",
    *     tags={"User"},
    *     summary="User change password ",
    *     description="User change password",
    *     security={{"bearerAuth":{}}},
    *     operationId="changePassword",
    *  *   @OA\Parameter(
    *         name="oldpassword",
    *         in="query",
    *         description="Enter old password",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="12345678",
    *             
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="new_password",
    *         in="query",
    *         description="Enter new password",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="12345678",
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
    public function changePassword(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'oldpassword' => 'required',
                'new_password' => 'required',
            ]);

            if ($validation->fails()) {

                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $user = User::where(['id' => $user])->first();
            $hashedPassword = $user->password;
            if (Hash::check($request->oldpassword, $hashedPassword)) {
                // The passwords match
                $requestData = $request->all();
                $user->update(['password' => bcrypt($requestData['new_password'])]);
                return res_success("Password changed successfully.");
            } else {

                return res_failed1('Old passwords do not match');
            }

        } catch (Exception $e) {

            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Delete(
    *     path="/api/delete-account",
    *     tags={"User"},
    *     summary="User Delete ",
    *     description="User Delete",
    *     security={{"bearerAuth":{}}},
    *     operationId="deleteAccount",
    
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    public function deleteAccount(Request $request){
         $delete = auth('api_user')->user()->delete();
        if ($delete) {
            return res_success("Account deleted successfully.");
        }

    }
    
    public function check_device(Request $request){
          try {
            $validation = Validator::make($request->all(), [
                'device_id' => 'required',
            ]);
            if ($validation->fails()) {
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $validation->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $user1 = DB::table('user_log_history')->where(['user_id' => $user])->latest('login_time')->pluck('device_id')->first();
            if ($user1 != $request->device_id) {
                return res_failed3("You Account is logged into another Device.");
            }else{
                return res_success();
            }
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
}