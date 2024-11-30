<?php

namespace App\Http\Controllers;

use App\JWT;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class JWTController extends Controller
{
    public $loginAfterSignUp = true;

    public function phoneOrEmail($phoneOrEmail)
    {
        $login = $phoneOrEmail;
        if (is_numeric($login)) {
            $field = 'phone';
        } else {
            $field = 'email';
        }
        request()->merge([$field => $login]);

        return $field;
    }

    public function login()
    {
    
        if (!empty($this->request['phoneOrEmail']) && !empty($this->request['password'])) {
            $field = $this->phoneOrEmail($this->request['phoneOrEmail']);
            if (!$token = auth()->attempt([$field => $this->request['phoneOrEmail'], 'password' => $this->request['password']])) {
                $this->response['statusCode'] = 403;
                return $this->response();
            }
            $this->data['access_token'] = $token;
            
            $this->response['success'] = true;
            $userResponse = User::where('id', auth()->user()->id)->first();
            $userResponse->makeHidden(['password', 'passwordRecovery']);
            $this->data['auth'] =  $userResponse;
        }
        return $this->response();
    }

    public function register()
    {

        $messages = [
            'email.unique' => 'Email đã tồn tại',
            'phone.unique' => 'Số điện thoại đã được đăng kí',
        ];

        $validator = Validator::make($this->request, [
            'email' => 'required|max:255|unique:users',
            'phone' => 'required|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->response['errorLists'] = $validator->errors();
            $this->response['statusCode'] = 403;
            return $this->response();
        }
        $user = new JWT();
        $user->phone = $this->request['phone'];
        $user->name = $this->request['name'];
        $user->email = $this->request['email'];
        $user->password = Hash::make($this->request['password']);
        $user->passwordRecovery = Crypt::encrypt($this->request['password']);
        $user->save();

        try {
            $auth = User::where('id', $user->id)->first();
            $auth->makeHidden(['password', 'passwordRecovery']);
            $accessToken = auth()->attempt(['phone' => $user->phone, 'password' => $this->request['password']]);
            $success = true;
            return response()->json(compact('auth', 'accessToken', 'success'), 200);
        } catch (\Exception $e) {
            $user_delete = User::find($user->id);
            $user_delete->delete();
            return $this->response();
        }
    }


    public function logout(Request $request)
    {
        try {
            auth()->logout();
            return response()->json([
                'success' => true,
                'code' => 200,
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => true,
                'code' => 200,
            ]);
        }
    }
}
