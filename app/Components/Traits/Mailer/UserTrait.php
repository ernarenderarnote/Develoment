<?php
namespace App\Components\Traits\Mailer;

use Mail;

use Illuminate\Http\Request;

use App\Models\User;

trait UserTrait
{
    public static function sendWelcomeEmail(User $user, array $data = [])
    {
        $subject = trans('labels.welcome_email_subject');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.user.welcome', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }

    public static function sendUserRegisteredMail(User $user, array $data = [])
    {
        $plan = session('plan');
        $preparedStore = session('preparedStore');
        $plan ? $subject = trans('labels.user_registered_with_plan_subject') : $subject = trans('labels.user_registered_without_plan_subject');
        $store = NULL;
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['plan'] = $plan;
        $data['store'] = NULL;
        $data['logo']=\Config::get('beautymail.logo');
        if ($preparedStore != NULL) {
            $store = $preparedStore['attributes']['domain'];
            $data['store'] = $store;
        }

        Mail::send('emails.user.registered', $data, function ($m) use ($user, $subject, $plan, $store) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), 'MS Admin')
                ->subject($subject);
        });
    }

    public static function sendUserActivatedEmail(User $user, array $data = [])
    {
        $subject = trans('labels.account_activated_email_subject');
        $data['user'] = $user;
        $data['subject'] = $subject;

        Mail::send('emails.user.account_activated', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }

}
