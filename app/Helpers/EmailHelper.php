<?php

namespace App\Helpers;

class EmailHelper
{
    public static function sendEmailWithCurlForSignup($recipientEmail, $recipientName, $emailOtp, $templateId)
    {
        $curl = curl_init();

        $data = [
            "recipients" => [
                [
                    "to" => [
                        [
                            "email" => $recipientEmail,
                            "name" => $recipientName,
                        ],
                    ],
                    "variables" => [
                        "VAR1" => $emailOtp,
                        "VAR2" => $recipientName,
                    ],
                ],
            ],
            "from" => [
                "email" => "auricle@auricle.co.in",
            ],
            "domain" => "auricle.co.in",
            "template_id" => $templateId,
        ];

        $payload = json_encode($data);

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/email/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'authkey: 406363AzMgDTNdZ650d3803P1',
                'Cookie: PHPSESSID=c3a9qlajeq6vrsdn31t9dg47a2',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
     public static function sendEmailWithCurlForLiveClass($recipientEmail, $recipientName, $starttime,$completeDate,$zoom_link, $templateId)
    {
        $curl = curl_init();

        $data = [
            "recipients" => [
                [
                    "to" => [
                        [
                            "email" => $recipientEmail,
                            "name" => $recipientName,
                        ],
                    ],
                    "variables" => [
                        "VAR1" => $recipientName,
                        "VAR2" => $starttime,
                        "VAR3" => $completeDate,
                        "VAR4" => $zoom_link,
                    ],
                ],
            ],
            "from" => [
                "email" => "auricle@auricle.co.in",
            ],
            "domain" => "auricle.co.in",
            "template_id" => $templateId,
        ];

        $payload = json_encode($data);

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/email/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'authkey: 406363AzMgDTNdZ650d3803P1',
                'Cookie: PHPSESSID=c3a9qlajeq6vrsdn31t9dg47a2',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
      public static function sendEmailWithCurlForReminder($recipientEmail, $recipientName,$zoom_link, $templateId)
    {
        $curl = curl_init();

        $data = [
            "recipients" => [
                [
                    "to" => [
                        [
                            "email" => $recipientEmail,
                            "name" => $recipientName,
                        ],
                    ],
                    "variables" => [
                        "VAR1" => $zoom_link,
                        "VAR2" => $recipientName,
                        "VAR3" => $recipientName,
                    ],
                ],
            ],
            "from" => [
                "email" => "auricle@auricle.co.in",
            ],
            "domain" => "auricle.co.in",
            "template_id" => $templateId,
        ];

        $payload = json_encode($data);

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/email/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'authkey: 406363AzMgDTNdZ650d3803P1',
                'Cookie: PHPSESSID=c3a9qlajeq6vrsdn31t9dg47a2',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
