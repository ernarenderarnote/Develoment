<?php
namespace App\Components\Traits\Mailer;

use Mail;

use App\Models\User;

trait PaymentTrait
{
    public static function sendPaymentReceiptEmail(User $user, array $data = [])
    {
        $subject = trans('labels.you_have_been_billed');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.payment.payment-receipt', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
    public static function sendAutoPaymentFailedNotificationEmail(User $user, array $data = [])
    {
        $subject = trans('labels.automatic_payment_failed');
        $data['user'] = $user;
        $data['subject'] = $subject;
        
        Mail::send('emails.payment.automatic-payment-failed', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
}
