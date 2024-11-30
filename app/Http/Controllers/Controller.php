<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper\FinalVariable;
use App\Helper\Helper;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response = [];
    protected $request = [];
    protected $message_error = 'Có lỗi xảy ra vui lòng thử lại';
    protected $dataSendView = [];
    protected $user;
    protected $data;
    protected $auth;
    protected $status_init = 'publish';
    protected $extension_image = ['PNG', 'png', 'jpg', 'jpeg'];
    protected $condition = [];
    protected $limitHome;
    
    protected $limit;
    public function __construct()
    {
        $request = request();
        //config
        $this->limit = 15;
        $this->limitHome = 10;
        $this->response = [
            'statusCode' => 200,
            'errorLists' => [],
            'success' => false,
            'message' => '',
            'data' => '',

        ];
        $this->request = $request->all();
        $this->request['method'] = request()->method();
        $this->dataSendView['user'] = \Illuminate\Support\Facades\Auth::guard('web')->user();
        $this->user = \Illuminate\Support\Facades\Auth::guard('jwt')->user();
        $this->auth = \Illuminate\Support\Facades\Auth::class;
        $this->dataSendView['prefix'] = config('app.prefix');
        $this->dataSendView['redirectTo'] = '/' . config('app.prefix') . '/';
        $this->condition = ['status' => 'publish'];

    }

    protected function setCreatedId($prop){
        $prop->created_id = $this->user->id;
    }
    protected function setUpdatedId($prop){
        $prop->updated_id = $this->user->id;
    }
    protected function setDeleteId($prop){
        $prop->deleted_id = $this->user->id;
    }

    protected function setUpdateField($field,$model){
        if(isset($this->request[$field])){
         return $this->request[$field];
        }else{
            return $model[$field];
        }
    }


   

    public function view($path)
    {
        return view($path, $this->dataSendView);
    }

    public function returnJson($json)
    {
        return response()->json($json);
    }
    public function convertCreatedAt($data)
    {
        foreach ($data as  $item) {
            $formatDate = Carbon::parse($item['created_at'])->format("d/m/Y - H:i:s");
            $item['createdAt'] = $formatDate;
        }
        return $data;
    }
    public function convertCreatedAtCustom($data, $custom)
    {
        foreach ($data as  $item) {
            $formatDate = Carbon::parse($item['created_at'])->format($custom);
            $item['createdAt'] = $formatDate;
        }
        return $data;
    }

    public function doAction($controller = '')
    {

        $getPrefix = request()->route()->getPrefix();
        if (!empty($this->request['action'])) {
            if ($getPrefix == '/' . $this->dataSendView['prefix']) {
                return $this->executeDoAction(false);
            } else if ($getPrefix == 'api') {
                return $this->executeDoAction(true, $controller);
            } else {

                return response()->json(FinalVariable::$response);
            }
        } else {
            if ($getPrefix == '/' . config('app.prefix')) {
                return $this->view('errors.404');
            }
            return response()->json(FinalVariable::$response);
        }
    }


    public function executeDoAction($api, $controller = '')
    {
        $function = '';
        $action = $this->request['action'];
        $method = $this->request['method'];
        if ($method == "POST") {
            $function = '_';
            $function .= Helper::convertObjectName($action, true);
            if (method_exists($this, $function)) {
                return $this->excuteFunction($function, $controller);
            }
        }


        if ($method == "GET") {
            $function_get =  Helper::convertObjectName($action, true);
            if (method_exists($this, '_' . $function_get)) {
                return $this->returnJson(FinalVariable::$method_invalid);
            }
            if (method_exists($this, $function_get)) {
                if ($api) {
                    if (method_exists($this, $function_get)) {
                        return $this->excuteFunction($function_get, $controller);
                    }
                }
                return $this->view('errors.method');
            }
        } else if ($method == "POST") {
            $function_post = Helper::convertObjectName($action, true);
            if (method_exists($this, $function_post)) {
                if ($api) {
                    return $this->returnJson(FinalVariable::$method_invalid);
                }
                return $this->view('errors.method');
            }
        }

        if ($api) {
            return $this->returnJson(FinalVariable::$not_found);
        }
        return $this->view('errors.404');
    }

    private function excuteFunction($function, $controller = '')
    {

        $characterCheckRole = 'z';
        if ($function[1] == $characterCheckRole || $function[0] == $characterCheckRole) {
            $modelUser = new ModelsUser();
            $user = $modelUser->getDepartmentList($this->user->id);
            if (!empty($user->roles)) {
                $controllerPath = "App\Http\Controllers\API";
                $flag = false;
                $stringFunctionRole = $function;
                if ($stringFunctionRole[0] == '_' || $stringFunctionRole[0] == 'z') {
                    $stringFunctionRole =  substr_replace($stringFunctionRole, '', 0, 1);
                }
                if ($stringFunctionRole[0] == '_' || $stringFunctionRole[0] == 'z') {
                    $stringFunctionRole =  substr_replace($stringFunctionRole, '', 0, 1);
                }
                foreach ($user->roles as $item) {
                    $explodeCode = explode("_", $item->code);
                    $controllerFromCode = ucfirst(strtolower($explodeCode[0]));
                    $actionFromCode =  ucfirst(strtolower($explodeCode[1]));
                    $controllerCheck = $controllerPath . '\\' . $controllerFromCode . 'Controller';
                    if (($actionFromCode == mb_substr($stringFunctionRole, 0, strlen($actionFromCode))) && $controllerCheck == $controller) {
                        $flag = true;
                    }
                }
                if ($flag) {
                    return $this->{$function}();
                } else {
                    return $this->returnJson(FinalVariable::$not_permission);
                }
            }
            return $this->returnJson(FinalVariable::$not_permission);
        } else {
            return $this->{$function}();
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60
        ]);
    }

    protected function response($data = null)
    {
        $this->response['data'] =  $this->data;
        $data = $data ? $data : $this->response;
        return response()->json($data);
    }

    protected function pushTimeStartOrEnd($flag = false)
    {
        if ($flag) {
            return " 00:00:00";
        }
        return " 11:59:59";
    }


    protected function responseStatus($code = 200, $success = true, $message = [])
    {
        return response()->json(['statusCode' => $code, 'success' => $success, 'data' => $message]);
    }
}
