<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Services\UserServices;
use App\Models\Icon;
use App\Models\LogMain;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function user()
    {

        $auth = $this->user;
        if (!empty($auth)) {
            $user = new User();
            $this->data =  $user->getDepartmentList($auth->id);
            return $this->response();
        }
        return $this->response();
    }

    public function getAll()
    {
        $this->response['users'] = User::all();
        return $this->response();
    }
}
