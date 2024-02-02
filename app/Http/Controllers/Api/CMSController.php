<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentManagement;
use App\Models\Faq;
use DB;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;

class CMSController extends Controller
{
    /**
    *  @OA\Get(
    *     path="/api/faq-lists",
    *     tags={"CMS"},
    *     summary="FAQ",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="faqList",
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
    //Get Faq 
    public function faqList()
    {
        $faq = Faq::get();
        $data = [
            'faq' => $faq
        ];
        return res_success('Success', $data);
    }

    /**
    *  @OA\Get(
    *     path="/api/cms-lists",
    *     tags={"CMS"},
    *     summary="CMS ",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="cmsList",
    * *  @OA\Parameter(
    *         name="cms_id",
    *         in="query",
    *         description="Enter cms id (2=>Privacy Policy,3=>About US,4=>Terms & Conditions)",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="2",
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
    //Get Cms 
    public function cmsList(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'cms_id' => 'required'
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }

            $cms = ContentManagement::where('id', $request->cms_id)->first();


            return res_success('Success', $cms);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        }
    }
    /**
    *  @OA\Post(
    *     path="/api/contact-us",
    *     tags={"CMS"},
    *     summary="Contact us",
    *     description="Multiple status values can be provided with comma separated string",
    *     security={{"bearerAuth":{}}},
    *     operationId="contactUs",
    *  @OA\Parameter(
    *         name="title",
    *         in="query",
    *         description="Enter Title",
    *         required=false,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="Hello world!",
    *            
    *         )
    *     ),
    * 
    * *  @OA\Parameter(
    *         name="description",
    *         in="query",
    *         description="Enter description",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="This is description",
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
    //contactUs
    public function contactUs(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'description' => 'required'
            ]);
            if ($data->fails()) {

                $result['code'] = 0;
                $result['status'] = config('messages.http_codes.validation');
                $result['message'] = $data->messages()->first();
                $result['data'] = (Object) [];
                return response()->json($result);
            }
            $user = auth('api_user')->user()->id;
            $data = ContactUs::create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
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
    
    public function get_razorpay_key(Request $request){
       try {
            $cms = DB::table('payment_key')->select('api_key')->first();
            return res_success('Success', $cms);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        } 
    }
    
    public function get_contact_details(Request $request){
       try {
            $cms = DB::table('contact_details')->select('email','phone')->first();
            return res_success('Success', $cms);
        } catch (Exception $e) {
            return $e->getMessage();
            die;
            return res_catch('Something went wrong!');
        } 
    }
}