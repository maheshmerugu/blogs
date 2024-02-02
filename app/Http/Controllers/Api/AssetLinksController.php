<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssetLinksController extends Controller
{
    public function getAssetLinks()
    {
        $response = [
            [
                'relation' => ['delegate_permission/common.handle_all_urls'],
                'target' => [
                    'namespace' => 'android_app',
                    'package_name' => 'com.app.auricle',
                    'sha256_cert_fingerprints' => [
                        'FA:A8:E0:91:7D:01:CB:CD:B0:2D:3F:52:34:F4:A5:8A:BE:58:7B:1B:D5:BF:E5:3A:04:19:DC:62:54:AF:57:C5',
                    ],
                ],
            ],
        ];

        return response()->json($response);
    }
}
