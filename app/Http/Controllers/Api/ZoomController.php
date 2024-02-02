<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    public function generateJwtToken(Request $request)
    {
        try {
            $meetingId = $request->input('meetingId');
            $role = $request->input('role');
            $apiKey = env('ZOOM_API_KEY', '');
            $apiSecret = env('ZOOM_API_SECRET', '');
            $CLIENT_ID = env('CLIENT_ID', '');
            $CLIENT_SECRET = env('CLIENT_SECRET');
            $accountId = env('ACCOUNT_ID');
            $ZOOM_USER_ID = env('ZOOM_USER_ID');

            // Zoom API URL for token generation
            $url = 'https://zoom.us/oauth/token';
            $base = base64_encode("$CLIENT_ID:$CLIENT_SECRET");
            $client = new Client();

            // Make a POST request to generate the access token
            $response = $client->post($url, [
                'headers' => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Authorization" => "Basic $base",
                ],
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ],
            ]);

            // Decode the response
            $data = json_decode($response->getBody(), true);

            // Access the access token
            $accessToken = $data['access_token'];

            // Generate JWT payload
            $time = time();
            $payload = [
                'appKey' => $apiKey,
                'sdkKey' => $apiKey,
                'mn' => $meetingId,
                'role' => $role,
                'iat' => $time,
                'exp' => $time + 60 * 60 * 6, // Six hours
                'tokenExp' => $time + 60 * 60 * 6, // Six hours
            ];

            // Encode the payload into a JWT
            $jwtToken = JWT::encode($payload, $apiSecret, 'HS256');

            $client2 = new Client();
            $zakurl = 'https://api.zoom.us/v2/users/' . $ZOOM_USER_ID . '/token?type=zak';

            try {
                // Make a GET request to the Zoom API to generate the Zoom Access Token (ZAK)
                $response2 = $client2->get($zakurl, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                    ],
                ]);

                // Decode the response
                $data2 = json_decode($response2->getBody(), true);

                $responseData = [
                    'code' => 1,
                    'status' => 200,
                    'message' => 'Success',
                    'data' => [
                        'jwtToken' => $jwtToken,
                        'zak' => $data2['token'],
                    ],
                ];
                return response()->json($responseData);

            } catch (\Exception $e2) {
                // Log the exception or handle it appropriately for response2
                return response()->json(['error2' => $e2->getMessage()], 500);
            }

        } catch (\Exception $e) {
            // Log the exception or handle it appropriately for response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

/*
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
public function generateJwtToken(Request $request)
{
$meetingId = $request->input('meetingId');
$role = $request->input('role');
$apiKey = env('ZOOM_API_KEY', '');
$apiSecret = env('ZOOM_API_SECRET', '');
$CLIENT_ID = env('CLIENT_ID', '');
$CLIENT_SECRET = env('CLIENT_SECRET');
$accountId = env('ACCOUNT_ID');
$ZOOM_USER_ID = env('ZOOM_USER_ID');

// Zoom API URL for token generation
$url = 'https://zoom.us/oauth/token';
$base = base64_encode("$CLIENT_ID:$CLIENT_SECRET");
$client = new Client();

// Make a POST request to generate the access token

$response = $client->post($url, [
'headers' => [
"Content-Type" => "application/x-www-form-urlencoded",
"Authorization" => "Basic $base",
],
'form_params' => [
'grant_type' => 'account_credentials',
'account_id' => $accountId,
],
]);

// Decode the response
$data = json_decode($response->getBody(), true);
//dd($data);
// Access the access token
$accessToken = $data['access_token'];
// Generate JWT payload
$time = time();
$payload = [
'appKey' => $apiKey,
'sdkKey' => $apiKey,
'mn' => $meetingId,
'role' => $role,
'iat' => $time,
'exp' => $time + 60 * 60 * 6, // Six hours
'tokenExp' => $time + 60 * 60 * 6, // Six hours
];

// Encode the payload into a JWT
$jwtToken = JWT::encode($payload, $apiSecret, 'HS256');

$client2 = new Client();
$zakurl = 'https://api.zoom.us/v2/users/' . $ZOOM_USER_ID . '/token?type=zak';
// Make a GET request to the Zoom API to generate the Zoom Access Token (ZAK)
$response2 = $client2->get($zakurl, [
'headers' => [
'Authorization' => 'Bearer ' . $accessToken,
'Content-Type' => 'application/json',
],
]);
$data = json_decode($response2->getBody(), true);

return response()->json(['jwtToken' => $jwtToken, 'zak' => $data['token']]);

}

}

 */
