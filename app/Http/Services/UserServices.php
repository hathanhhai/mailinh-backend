<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\Icon;
use App\Models\LogMain;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserServices 
{

    

    public static function getAll(){

        return User::all();

    }


}
