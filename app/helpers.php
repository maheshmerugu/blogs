<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

if (!function_exists('generateOtp')) {

    /**
     * @return string
     */
    function generateOtp()
    {
        $randomOtp = mt_rand(1000, 9999);

        return $randomOtp ? $randomOtp : 1234;
    }
}