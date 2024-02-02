<?php


use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

if (!function_exists('res')) {
    function res($status = 200, $msg = '', $data = [])
    {
        return response()->json([
            'code' => 1,
            'status' => $status,
            'message' => $msg,
            'data' => (Object) $data
        ]);
    }
}

if (!function_exists('res_success')) {
    function res_success($msg = 'Success!', $data = [])
    {
        return response()->json([
            'code' => 1,
            'status' => 200,
            'message' => $msg,
            'data' => (Object) $data
        ]);
    }
}

if (!function_exists('res_success1')) {
    function res_success1($msg = 'Success!', $data = [])
    {
        return response()->json([
            'code' => 1,
            'status' => 200,
            'message' => $msg,
            'data' => $data
        ]);
    }
}

if (!function_exists('res_failed')) {
    function res_failed($msg = 'Failed!')
    {
        return response()->json([
            'code' => 0,
            'status' => 422,
            'message' => $msg,
            'data' => (Object) []
        ]);
    }
}
if (!function_exists('res_failed1')) {
    function res_failed1($msg = 'Failed!')
    {
        return response()->json([
            'code' => 2,
            'status' => 423,
            'message' => $msg,
            'data' => (Object) []
        ]);
    }
}

if (!function_exists('res_failed3')) {
    function res_failed3($msg = 'Failed!')
    {
        return response()->json([
            'code' => 22,
            'status' => 999,
            'message' => $msg,
            'data' => 999
        ]);
    }
}


if (!function_exists('res_catch')) {
    function res_catch($msg = 'Something went wrong!')
    {
        return response()->json([
            'code' => 0,
            'status' => 500,
            'message' => $msg,
            'data' => (Object) []
        ]);
    }
}

/*if(!function_exists('getEmployee')){
function getEmployee(Request $request){
$token = $request->bearerToken();
$id = (new Parser())->parse($token)->getClaim('jti');
$oauth = DB::table('oauth_access_tokens')->where('id', $id)->first();
$employeeId = $oauth->name;
return Employee::find($employeeId);
}
}*/