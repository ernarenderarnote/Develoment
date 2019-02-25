<?php
namespace App\Components\Traits\Mailer;

use Mail;
use Cache;
use Carbon\Carbon;

use App\Models\User;

trait OrderTrait
{
    public static function sendOrderActionRequiredNotificationEmail(User $user, array $data = [])
    {
        $subject = trans('labels.order_action_required');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.order.order-action-required', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
    
    public static function sendOrderProductAdminActionRequiredNotificationEmail(array $data = [])
    {
        $notifyIntervalMinutes = config('settings.emails.intervals.order-product-admin-action-required');
        
        // we will send email for each order only once per interval
        $cacheValueName = static::class.':'.(__FUNCTION__).':'.$data['order']->id.':'.$data['product']->id;
        if (Cache::has($cacheValueName)) {
            return;
        }
        
        Cache::add(
            $cacheValueName,
            true,
            Carbon::now()->addMinutes($notifyIntervalMinutes)
        );
        
        $subject = trans('labels.order_action_required');
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.order.order-product-admin-action-required', $data, function ($m) use ($subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), getenv('MAIL_FROM_NAME'))
                ->subject($subject);
        });
    }
    
    public static function sendOrderActionRequiredAdminEmail(array $data = [])
    {
        $subject = trans('labels.order_action_required');
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.order.order-admin-action-required', $data, function ($m) use ($subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to(getenv('EMAIL_SUPPORT'), getenv('MAIL_FROM_NAME'))
                ->subject($subject);
        });
    }
    
    public static function sendOrderCanBeCompletedEmail(User $user, array $data = [])
    {
        $subject = trans('labels.order_can_be_completed');
        $data['user'] = $user;
        $data['subject'] = $subject;
        $data['logo']=\Config::get('beautymail.logo');
        Mail::send('emails.order.order-resolved-action-required', $data, function ($m) use ($user, $subject) {
            $m
                ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
                ->to($user->email, $user->name)
                ->subject($subject);
        });
    }
}
