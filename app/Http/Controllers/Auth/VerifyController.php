<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterFormRequest;
use App\Models\User;


class VerifyController extends Controller
{
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function verify($code)
    {
        if (!$code) {
            flash()->error(trans('messages.confirmation_code_not_found'));
            return redirect(url('/login'));
        }

        $user = User::whereConfirmationCode($code)->first();

        if (!$user) {
            flash()->error(trans('messages.confirmation_code_not_found_or_used'));
            return redirect(url('/login'));
        }

        $user->is_confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        
        flash()->success(trans('messages.account_confirmed'));

        return redirect(url('/login'));
    }
}
