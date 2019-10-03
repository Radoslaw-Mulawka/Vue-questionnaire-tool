<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Laravue\Models\User;
use App\Notifications\EmailVerification;
use App\Rules\EmailExist;
use App\Rules\UserActive;
use App\Rules\UserNoActive;
use App\Traits\ApiResponser;
use DateTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'email', 'min:5', 'max:255', new UserActive, new UserNoActive],
            'password' => ['required', 'min:6', 'confirmed'],
            'accept_terms' => ['required', 'accepted']
        ]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validatorSendAgain(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'max:255', 'min:5', 'email', new UserActive, new EmailExist],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'accept_terms' => 1,
            'verification_hash' => User::generateVerificationHash()
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return ApiResponser
     */
    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'accept_terms');

        $validator = $this->validator($credentials);

        if ($validator->fails()) {
            return $this->respondValidationError($validator->errors()->first(), $validator->messages());
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        event(new Registered($user = $this->create($data)));
        $user->notify(new EmailVerification($user));

        return $this->respondSuccess([], trans('auth.register_succes'));
    }

    /**
     * @param $hash
     * @return ApiResponser
     * @throws \Exception
     */
    public function verify($hash)
    {
        /** @var User $user */
        $user = User::where('verification_hash', $hash)->first();

        if (is_null($user)) {
            return $this->respondValidationError(trans('app.wrong_url'), []);
        }

        if ($user->isVerified() == 1) {
            return $this->respondValidationError(trans('auth.verify_error_message'), []);
        }

        $user->setToVerified();
        $user->email_verified_at = new DateTime('now');
        if ($user->save()) {
            $user->assignRole('user');
            return $this->respondSuccess([], trans('auth.verify_success_message'));
        }
    }

    /**
     * @param Request $request
     * @return ApiResponser
     */
    public function sendAgain(Request $request)
    {
        $email = $request->only('email');

        $validator = $this->validatorSendAgain($email);

        if ($validator->fails()) {
            return $this->respondValidationError($validator->errors()->first(), $validator->messages());

        }

        $user = User::where('email', $email)->first();

        $user->notify(new EmailVerification($user));

        return $this->respondSuccess([], trans('auth.remind_success'));
    }

}
