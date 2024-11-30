<?php


namespace App\Helper;


class FinalVariable
{

    public static $response = ['success' => false, 'status' => 403, 'message' => 'Request Invalid'];
    public static $token = ['success' => false, 'status' => 401, 'message' => 'Token Invalid'];
    public static $method_invalid = ['success' => false, 'code' => 405, 'message' => 'Method Invalid'];
    public static $not_found = ['success' => false, 'status' => 404, 'message' => 'Not Found API'];
    public static $not_permission = ['success' => false, 'status' => 403, 'message' => "You don't have permission"];

    public static function urlPrefix($string){
        return config('app.prefix').'/'.$string;
    }





}
