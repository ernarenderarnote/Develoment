<?php
namespace App\Components\Traits\Mailer;

use Mail;

use App\Models\User;

trait ProductVariantTrait
{
    public static function sendProductVariantApprovedEmail(User $user, array $data = [])
    {
        $subject = trans('messages.product_variant_approved');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.product-variant.approved', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
    public static function sendProductVariantDeclinedEmail(User $user, array $data = [])
    {
        $subject = trans('messages.product_variant_declined');
        $data['user'] = $user;
        $data['subject'] = $subject;
        
        Mail::send('emails.product-variant.declined', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
    public static function sendProductVariantOnModerationEmail(array $data = [])
    {
        $subject = trans('messages.product_variant_sent_on_moderation');
        $data['subject'] = $subject;
        
        Mail::send('emails.product-variant.on-moderation', $data, function ($m) use ($subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), getenv('MAIL_FROM_NAME'))
                ->subject($subject);
        });
    }
    
    
}
