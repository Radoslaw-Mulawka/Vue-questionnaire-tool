<?php

namespace App\Http\Controllers\Auth;

use App\Laravue\Models\PasswordReset;
use App\Notifications\UserResetPasswordNotification;
use App\Rules\EmailExist;
use App\Rules\UserNoActive;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class ForgottenPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }


    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validatorSendForgottenPassword(array $data) {
        return Validator::make($data, [
            'email' => ['required', 'max:255', 'min:5', 'email', new UserNoActive, new EmailExist],
        ]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validatorResetPassword(array $data) {
        return Validator::make($data, [
            'email' => ['required', 'max:255', 'min:5', 'email', new UserNoActive, new EmailExist],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
    }

    public static function generateToken()
    {
        return Str::random(40);
    }


    public function forgotten(Request $request) {

        $email = $request->only('email');

        $validator = $this->validatorSendForgottenPassword($email);

        if ($validator->fails()) {
            return $this->respondValidationError('', $validator->messages());
        }

        $passwordReset = new PasswordReset();
        $token = self::generateToken();
        $user = User::where('email', $email)->first();

        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->created_at = new \DateTime('now');
        $passwordReset->save();

        $user->notify(new UserResetPasswordNotification($token));

        return $this->respondSuccess([], trans('passwords.sent'));
    }

    /**
     * @param $token
     * @return ApiResponser
     */
    public function verify($token) {

        $userToken = PasswordReset::where('token', '=', $token)->first();
        if ($userToken === null) {
            return $this->respondValidationError(trans('app.wrong_url'), []);
        } else {
            return $this->respondSuccess([], 'Poprawny token');
        }
    }

    /**
     * @param Request $request
     * @return ApiResponser
     */
    public function reset(Request $request) {

        $credentials = $request->only('email', 'password', 'password_confirmation');

        $validator = $this->validatorResetPassword($credentials);

        if ($validator->fails()) {
            return $this->respondValidationError('', $validator->messages());
        }

        $passwordReset = PasswordReset::where('email', $credentials)->first();
        $user = User::where('email', $credentials)->first();

        $user->password = Hash::make($request->password);
        if ($user->save()) {
            $passwordReset->delete();
            return $this->respondSuccess([], trans('passwords.reset'));
        }


    }
}
