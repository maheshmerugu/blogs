<?php
 function subscribePlan(Request $request)
    {
    // Validation rules for request parameters
    $rule = [
        'order_id' => "required",
        'plan_id' => "required",
        'subcription_for' => "required|in:1,2", //  1=>Video,2=>Notes
    ];

    // Custom error messages for validation rules
    $custom = [
        'order_id.required' => 'Please enter the order id.',
        'plan_id.required' => 'Please select the plan.',
        'subcription_for.required' => 'Please select subscription for (video or notes).',
    ];

    // Validate the request parameters
    $validator = Validator::make($request->all(), $rule, $custom);

    // If validation fails, send a web response with the error message
    if ($validator->fails()) {
        $this->sendWebResponse(false, $validator->errors()->first());
    }

    try {
        // Fetch the authenticated user
        $user = auth('api_user')->user();
        $user_id = $user->id;

        // Fetch plan details based on the provided plan_id
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

        // Calculate the expiration date of the subscription
        $expireDate = Carbon::now()->addMonths($plan->months);

        // Check if there is already a subscription with the provided order_id
        $checkOrderId = Subscription::where('user_id', $user_id)->first();
//dd($checkOrderId);
        // If a subscription with the order_id exists, return an error response
        if ($checkOrderId) {
            return res_failed1('You have already subscribed to the plan.');
        }

        // Fetch the coupon used for the current subscription
        $coupon = Coupon::where('code', $usedCouponCode)->first();

        // Increase the coupon usage count if a coupon is used
        if ($coupon) {
            $coupon->increment('usage_count');
        }

        // Create a new subscription record in the database
        $data = Subscription::create([
            'user_id' => $user_id,
            'order_id' => $request->order_id,
            'plan_id' => $request->plan_id,
            'price' => $plan->payble_amount,
            'start_date' => Carbon::today(),
            'expiry_date' => $expireDate,
            'subcription_for' => $request->subcription_for,
        ]);
//dd($data);
        // Add additional data to the response
        $data->plan_name = $plan->plan_name;
        $data->watch_hours = $plan->watch_hours;

        // Return a successful response with the subscription details
        return res_success('Success', $data);

    } catch (Exception $e) {
        // If an exception occurs, return a status indicating failure
        return ['status' => false];
    }
}
