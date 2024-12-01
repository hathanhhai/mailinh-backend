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
            $me =  $user->getDepartmentList($auth->id);
            $permission = [];
            $roles = ['admin'];
            if (!empty($me->roles)) {
                foreach ($me->roles as $item) {
                    $explodeCode = explode("_", $item->code);
                    $elementPermisson = '';
                    foreach ($explodeCode  as $code) {
                        $elementPermisson = $elementPermisson . ucfirst(strtolower($code));
                    }
                    $permission[] =  $elementPermisson;
                }
            }
            $me->permissions = $permission;
            $me->roles = $roles;
            $this->data = $me;
            return $this->response();
        }
        return $this->response();
    }

    public function getAll()
    {
        $users = User::paginate(10);
        $data = $users->toArray()['data'];
        // $data = $users['data'];
        // foreach($users as $item){
        //     echo($item);
        //     // echo $item->current_page;
        // }
        $this->data['data'] =    $data;
        $paginate = $data = $users->toArray();
        unset($paginate['data']);
        $this->data['paginate'] = $paginate;


        return $this->response();
    }
}
