<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Icon;
use App\Models\LogMain;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    public function _department()
    {
        $this->response['teo']= 444;
        return $this->response();
    }


    public function getAll()
    {
        $this->response['ttttt'] = User::all();
        return $this->response();
    }
    
    public function _ZDeleteDepartment()
    {
        $this->response['_ZDeleteDepartment']= true;
        return $this->response();
    }






}
