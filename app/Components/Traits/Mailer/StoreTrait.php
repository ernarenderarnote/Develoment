<?php
namespace App\Components\Traits\Mailer;

use Mail;

use App\Models\User;

trait StoreTrait
{
    public static function sendStoreIntegrationWelcomeEmail(User $user, array $data = [])
    {
        $subject = trans('labels.welcome_integration_email_subject', [
            'app' => 'MonetizeSocial',
            'shop' => $data['store']->name
        ]);
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.store.welcome', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
}
