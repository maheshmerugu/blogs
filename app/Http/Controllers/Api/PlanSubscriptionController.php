<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\PaymentOrder;
use App\Models\PlanSubscription as Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanSubscriptionController extends Controller
{
    /**
     *  @OA\Get(
     *     path="/api/plan-lists",
     *     tags={"Subscription"},
     *     summary="Subscription Data",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="planList",
     *    @OA\Parameter(
     *          name="plan_type",
     *          required=true,
     *          description="Select type notes or video 1=>Video,2=>Notes",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="enum", enum={1,2}),
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
    //plans list
    public function planList(Request $request)
    {
        $rule = [

            'plan_type' => "required|In:1,2", //  1=>Video,2=>Notes
        ];

        $custom = [

            'subcription_for.required' => 'Please select plan type (video or notes).',

        ];

        $validator = Validator::make($request->all(), $rule, $custom);
        if ($validator->fails()) {
            $this->sendWebResponse(false, $validator->errors()->first());
        }
        try {
            $planList = Plan::where('plan_type', $request->plan_type)->get();
            $data = [
                'plans' => $planList,
            ];
            return res_success('Success', $data);

        } catch (Exception $e) {
            return $e->getMessage();
            return res_catch('Something went wrong!');
        }
    }

   public function validateCoupon(Request $request)
{
    try {
        // Validate request parameters
        $plan = Plan::where('id', $request->plan_id)->first();
        $request->validate([
            'coupon_code' => 'nullable|string', // Coupon code is optional
        ]);

        // Fetch plan details
        $plan = Plan::findOrFail($request->plan_id);

        // Check if payable amount is <= 1 rupee
        if ($plan->payble_amount <= 1) {
            return response()->json([
                'code' => 13,
                'status' => 400,
                'message' => 'Failure',
                'error' => "You can't apply this coupon code on this plan"
            ], 400);
        }


        // Fetch coupon details (if provided)
        $coupon = null;
        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                // Coupon code not found in the coupons table
                return response()->json([
                    'code' => 10,
                    'status' => 404,
                    'message' => 'Failure',
                    'error' => 'Coupon code is invalid'
                ], 404);
            }

            // Check if the coupon has expired
            if ($coupon->expiry_date <= now()) {
                return response()->json([
                    'code' => 11,
                    'status' => 404,
                    'message' => 'Failure',
                    'error' => 'Coupon code has expired'
                ], 404);
            }

            // Continue with percentage-based discount calculation
            $discountAmount = 0;

            // Check if a valid coupon is provided and it's percentage-based
            if ($coupon && $coupon->discount_type == 'percentage') {
                $discountAmount = ($plan->payble_amount * $coupon->discount) / 100;
            }

            // Calculate the final amount after applying the discount
            $amountAfterDiscount = ($plan->payble_amount - $discountAmount);

            // Increment coupon usage count
            if ($coupon) {
                $coupon->increment('usage_count');
            }

            // Return the final amount and discount amount
            return response()->json([
                'code' => 1,
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'id' => $coupon->id,
                    'username' => $coupon->username, // replace with the actual column name
                    'phonenumber' => $coupon->phonenumber, // replace with the actual column name
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'discount' => number_format($coupon->discount, 2), // format as 15.00
                    'expiry_date' => $coupon->expiry_date,
                    'status' => $coupon->status,
                    'created_at' => $coupon->created_at,
                    'updated_at' => $coupon->updated_at,
                    'usage_count' => $coupon->usage_count,
                    'Amount_after_discount' => $amountAfterDiscount,
                ]
            ]);
        }

        return response()->json([
            'code' => 1,
            'status' => 200,
            'message' => 'Success',
            'data' => [
                'Amount_after_discount' => $plan->payble_amount,
                'coupon' => null
            ]
        ]);
    } catch (\Exception $e) {
        // Handle exceptions if any
        return response()->json([
            'code' => 0,
            'status' => 500,
            'message' => 'Failure',
            'error' => 'Internal Server Error'
        ], 500);
    }
}


    /**
     *  @OA\Post(
     *     path="/api/order-generate",
     *     tags={"Subscription"},
     *     summary="Generate order id",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="orderGenerate",
     *  @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Enter plan id",
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
    //Order Generate

 public function orderGenerate(Request $request)
{
    try {
        // Fetch plan details
        $plan = Plan::where('id', $request->plan_id)->first();
        // Fetch user details
        $user = auth('api_user')->user();

        // Fetch coupon details (if provided)
        $couponCode = $request->coupon_code;

        $coupon = null;
        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                // Coupon code not found in the coupons table
                return response()->json(['status' => 'Failure', 'error' => 'Coupon code is invalid'], 404);
            }

            // Check if the coupon has expired
            if ($coupon->expiry_date <= now()) {
                return response()->json(['status' => 'Failure', 'error' => 'Coupon code has expired'], 404);
            }
        }

        // Continue with percentage-based discount calculation
        $discountAmount = 0;

        // If a valid coupon is provided and it's percentage-based
        if ($coupon && $coupon->discount_type == 'percentage') {
            $discountAmount = ($plan->payble_amount * $coupon->discount) / 100;
        }

        // Check if payble_amount is set and adjust amount accordingly
        if ($plan->payble_amount) {
            // Convert payble_amount from rupees to paisa (multiply by 100)
            $amountAfterDiscount = ($plan->payble_amount - $discountAmount) * 100;
        } else {
            // If payble_amount is not set, use the original amount
            $amountAfterDiscount = $plan->amount * 100;
        }

        // Call Razorpay API to generate the order
        $receipt = 'TXN' . time();
        $url = "https://api.razorpay.com/v1/orders";
        $basic_auth_token = base64_encode(env('RAZOR_KEY') . ":" . env('RAZOR_SECRET'));

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Set headers
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic " . $basic_auth_token,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // Set request data
        $data = '{"amount": ' . $amountAfterDiscount . ',
             "currency": "INR",
             "receipt": "' . $receipt . '",
             "partial_payment": false,
             "first_payment_min_amount": ' . $amountAfterDiscount . '}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Execute curl
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        // Handle Razorpay response
        $respData = json_decode($resp);

        // Update used_coupon for the authenticated user if a coupon is provided
        // if ($coupon) {
        //     $user->used_coupon = $coupon->code;
        //     $user->used_plan_id = $plan->id;
        //     $user->save();
        // }

        // Create PaymentOrder record
        $user_id = $user->id;
        $pdata = PaymentOrder::create([
            'user_id' => $user_id,
            'amount' => $amountAfterDiscount,
            'order_id' => $respData->id ?? '',
            'razor_details' => json_encode($respData),
        ]);

        // Return the final amount, discount amount, and order details
        $data = ['order_data' => $pdata, 'razor' => $respData];
        return res_success('Success', $data);
    } catch (\Exception $e) {
        // Handle exceptions if any
        return response()->json(['status' => 'Failure', 'error' => 'Internal Server Error'], 500);
    }
}

    /**
     *  @OA\Post(
     *     path="/api/subscribe-plan",
     *     tags={"Subscription"},
     *    summary="Which plan user want to subscribe for(Notes or Video)",
     *     description="Multiple status values can be provided with comma separated string",
     *     security={{"bearerAuth":{}}},
     *     operationId="subscribePlan",
     *  @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Enter plan id",
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
     *         name="order_id",
     *         in="query",
     *         description="Enter order id",
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
     *          name="subcription_for",
     *          required=true,
     *          description="Select for notes or video (1=>Video,2=>Notes )",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="enum", enum={1,2}),
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
    //User subscribe the plan
public function subscribePlan(Request $request)
{
    $rule = [
        'order_id' => "required",
        'plan_id' => "required",
        'subcription_for' => "required|in:1,2", //  1=>Video,2=>Notes
    ];

    $custom = [
        'order_id.required' => 'Please enter the order id.',
        'plan_id.required' => 'Please select the plan.',
        'subcription_for.required' => 'Please select subscription for (video or notes).',
    ];

    $validator = Validator::make($request->all(), $rule, $custom);
    if ($validator->fails()) {
        $this->sendWebResponse(false, $validator->errors()->first());
    }

    try {
        $user = auth('api_user')->user();
        $user_id = $user->id;

        // Fetch plan details
        $plan = Plan::where('id', $request->plan_id)->first();

        // Check if the user used a coupon code for this plan
        $usedCouponCode = $user->used_coupon ?? null;
        $usedPlanId = $user->used_plan_id ?? null;

        // Check if the user used a coupon code and it was for the same plan
        if ($usedCouponCode && $usedPlanId == $plan->id) {
            // Store the used coupon code and plan id in the users table
            $user->used_coupon = null; // Reset used_coupon field
            $user->used_plan_id = null; // Reset used_plan_id field
            $user->save();
        }

        // Continue with subscription logic
        $expireDate = Carbon::now()->addMonths($plan->months);
        $checkOrderId = Subscription::where('order_id', $request->order_id)->first();

        if ($checkOrderId) {
            return res_failed1('You have already subscribed to the plan.');
        }

// Fetch the coupon used for the current subscription
        $coupon = Coupon::where('code', $usedCouponCode)->first();

        // Increase the coupon usage count
        if ($coupon) {
            $coupon->increment('usage_count');
        }

        $data = Subscription::create([
            'user_id' => $user_id,
            'order_id' => $request->order_id,
            'plan_id' => $request->plan_id,
            'price' => $plan->payble_amount,
            'start_date' => Carbon::today(),
            'expiry_date' => $expireDate,
            'subscription_for' => $request->subcription_for,
        ]);

        $data->plan_name = $plan->plan_name;
        $data->watch_hours = $plan->watch_hours;

        return res_success('Success', $data);

    } catch (Exception $e) {
        return ['status' => false];
    }
}

}
