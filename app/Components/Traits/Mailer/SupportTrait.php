<?php
namespace App\Components\Traits\Mailer;

use Mail;

use App\Models\User;

trait SupportTrait
{
    public static function sendUserTicketOpenedEmail(User $user, array $data = [])
    {
        $subject = trans('messages.ticket_opened_user_email_subject');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.support.ticket-opened-user', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
    public static function sendAdminTicketOpenedEmail(array $data = [])
    {
        $subject = trans('messages.ticket_opened_admin_email_subject');
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.support.ticket-opened-admin', $data, function ($m) use ($subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), getenv('MAIL_FROM_NAME'))
                ->subject($subject);
        });
    }
}
