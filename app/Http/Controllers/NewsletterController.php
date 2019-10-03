<?php

namespace App\Http\Controllers;

use App\Laravue\Models\Newsletter;
use App\Laravue\Models\User;
use App\Notifications\SubscribeNewsletter;
use App\Notifications\UnsubscribeNewsletter;
use App\Rules\EmailExist;
use App\Rules\UserNoActive;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validatorSubscribe(array $data) {

        return Validator::make($data, [
            'email' => ['required', 'max:255', 'min:5', 'email', new UserNoActive, new EmailExist],
        ]);
    }


    public static function generateHash()
    {
        return Str::random(40);
    }

    /**
     * Subscire new email
     *
     * @param Request $request
     * @return ApiResponser
     */
    public function subscribe(Request $request) {
        $email = $request->only('email');
        $validator = $this->validatorSubscribe($email);

        $newsletter = new Newsletter();

        if ($validator->fails()) {
            return $this->respondValidationError('', $validator->messages());
        }

        $newsletter_check_status = Newsletter::where('email_newsletter', $request->email)->first();

        if(is_null($newsletter_check_status) || $newsletter_check_status->status === 0){
            $newsletter->email_newsletter = $request->email;
            $newsletter->status = 1;
            $newsletter->consent = 1;
            $newsletter->hash_unsubscribe = self::generateHash().'unsubscribe';
            $newsletter->hash_resubscribe = self::generateHash().'resubscribe';

            $newsletter->save();

            $user = User::where('email', $email)->first();

            $user->notify(new SubscribeNewsletter($newsletter->hash_unsubscribe));

            return $this->respondSuccess([], trans('app.newsletter_subscribe'));

        } else {
            if ($newsletter_check_status->status === 1){
                return $this->respondValidationError(trans('app.newsletter_subscribe_error'), []);
            }
        }
    }


    /**
     * Unsubscribe existing email
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe($hash) {

        $newsletter = Newsletter::where('hash_unsubscribe', $hash)->first();

        if(is_null($newsletter)) {
            return $this->respondValidationError(trans('app.newsletter_unsubscribe_hash_error'), []);
        }

        $newsletter->delete();

        $user = User::where('email', $newsletter['email_newsletter'])->first();

        $user->notify(new UnsubscribeNewsletter());

        return $this->respondSuccess([], trans('app.newsletter_unsubscribe'));

    }
}
