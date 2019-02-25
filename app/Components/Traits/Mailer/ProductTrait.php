<?php
namespace App\Components\Traits\Mailer;

use Mail;

use App\Models\User;

trait ProductTrait
{
    public static function sendProductApprovedEmail(User $user, array $data = [])
    {
        $subject = trans('messages.product_approved');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.product.approved', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }

    public static function sendProductDeclinedEmail(User $user, array $data = [])
    {
        $subject = trans('messages.product_declined');
        $data['user'] = $user;
        $data['subject'] = $subject;

        Mail::send('emails.product.declined', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }

    public static function sendProductOnModerationEmail(array $data = [])
    {
        $subject = trans('messages.product_sent_to_moderation');
        $data['subject'] = $subject;

        Mail::send('emails.product.on-moderation', $data, function ($m) use ($subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), getenv('MAIL_FROM_NAME'))
                ->subject($subject);
        });
    }

    /**
     * Send email to user when we get KZ notification
     * about some product out of stock
     */
    public static function sendProductOutOfStockNotificationEmail(User $user, array $data = [])
    {
        $subject = trans('messages.products_are_out_of_stock');
        $data['subject'] = $subject;

        Mail::queue('emails.product.out-of-stock', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }


}
