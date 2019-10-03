<?php
/**
 * File LoginController.phpp
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Controllers\Controller;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return ApiResponser
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->setHeader('Authorization', $token)->respondSuccess(new UserResource(Auth::user()), 'Login success');
        }

        return $this->respondUnauthorized(trans('auth.failed'));
    }

    public function logout()
    {
        $this->guard()->logout();
        return $this->respondSuccessNoContent();
    }

    public function user()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @return mixed
     */
    private function guard()
    {
        return Auth::guard();
    }
}
