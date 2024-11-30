<?php

namespace App\Http\Middleware;

use App\Helper\FinalVariable;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class Department
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        try {
        $flag = false;
        $auth = FacadesJWTAuth::parseToken()->authenticate();
        $userModel = new User();
        $user = $userModel->getDepartmentList($auth->id);

        if (!empty($user->roles)) {
            $destinationController =   class_basename(Route::current()->controller);
            foreach ($user->roles as $item) {
                $explodeCode = explode("_", $item->code);
                $controllerFromCode = ucfirst(strtolower($explodeCode[0]));
      
                if (($controllerFromCode == mb_substr($destinationController, 0, strlen($controllerFromCode)))) {
                    $flag = true;
                }
            }
            if ($flag) {
                return $next($request);
            } else {
                return response()->json(FinalVariable::$not_permission);
            }
        } else {
            return response()->json(FinalVariable::$not_permission);
        }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'status' => 500, 'message' => "Error while check middleware department:"+$e]);
        }
    }
}
