<?php

    return [

        'http_codes' => [
            
            'success'       => 200,
            'validation'    => 422,
            'unauthorized'  => 401,
            'not_found'     => 404,
            'server'        => 500,
        ],

        'error_messages'  => [

            'unauthorized'                  => 'Unauthorized',
            'create_pin'                    => 'Please first you create pin',
            'user_type_error'               => 'Please enter valid type for User.',
            'exist_mobile'                  => 'Mobile number already registered for User.',
            'exist_email'                  => 'Email already registered for User.',
            'exist_number'                  => 'Card already registered.',
            'exist_account'                  => 'Bank Account already registered.',
            'user_register_success'         => 'User registered successfully.',
            'user_card_success'             => 'User Card registered successfully.',
             'user_account_success'             => 'Your Account registered successfully.',
            'account_suspend'               => 'Your account is currently deactivated from Admin. Please contact for support.',
            'login_success'                 => 'Logged in successfully.',
            'verification_error'            => 'Please verify your account.',
            'credential_error'              => 'Invalid credentials. Please try again.',
            'email_not_exist'               => 'Email ID does not exist in our records!',
            'mobile_not_exist'               => 'Mobile does not exist in our records!',
            'otp_sent'                      => 'OTP sent successfully .',
            'otp_verified'                  => 'OTP verified successfully.',
            'server_error'                  => 'Something went wrong!',
            'invalid_otp'                   => 'Invalid OTP.',
            'password_reset'                => 'Password reset has been successfully done.',
            'vendor_not_found'              => 'Sorry! No vendors found.',
            'profile_updated'               => 'Your profile has been updated successfully.',
            'password_updated'              => 'Your password has been changed successfully.',
            'old_password_not_match'        => 'Old Password does not match.',
            'vendor_details'                => 'Sorry! Vendor details not found.',
            'vendor_not_found'              => 'Sorry! Vendor not found.',  
            'request_error'                 => 'Sorry! You can not send request.',          
            'request_sent'                  => 'Your request has been sent successfully to the Vendor & notify you shortly.',
            'new_request_title'             => 'New Request',
            'new_request_details'           => 'You have one new request from ',
            'no_request'                    => 'Sorry! No request found.',
            'no_booking'                    => 'Sorry! No booking found.',
            'vendor_type_error'             => 'Please enter valid type for Vendor.',
            'exist_mobile_vendor'           => 'Mobile number already registered for Vendor.',
            'vendor_register_success'       => 'Vendor registered successfully.',
            'status_updated'                => 'Your status updated successfully.',
            'bank_details_exist_error'      => 'Sorry! You have already added your Bank details.',
            'bank_details_saved'            => 'Your Bank details are saved successfully.',
            'bank_details_updated'          => 'Your Bank details are updated successfully.',
            'bank_details_update_error'     => 'You can not update it, it has been verified by Admin.',
            'no_data'                       => 'Sorry! No data found.',
            'review_success'                => 'Thanks for submitting your feedback.',
            'genrate_pin'                   => 'Pin Generated successfully',
            'reset_pin'                     => 'Pin reset has been successfully done.',
            'amount_add'                    => 'Amount added in wallet successfully',
            'check_pin_match'               => 'Pin Matched',
             'upload_document'               => 'Your document uploaded successfully!!'
        ],     
        
        'credentials'   => [
            
            'GOOGLE_CONSOLE_KEY'    =>  env('GOOGLE_CONSOLE_KEY'),
            'FCM_SERVER_KEY'        =>  env('SERVER_KEY'),
            'PAYPAL_ACCESS_TOKEN'   =>  env('PAYPAL_SANDOX_ACCESS_TOKEN'),
        ]   

    ];


