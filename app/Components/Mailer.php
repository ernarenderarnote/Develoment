<?php
namespace App\Components;

class Mailer
{
    use \App\Components\Traits\Mailer\StoreTrait;
    use \App\Components\Traits\Mailer\UserTrait;
    use \App\Components\Traits\Mailer\SupportTrait;
    use \App\Components\Traits\Mailer\PaymentTrait;
    use \App\Components\Traits\Mailer\ProductTrait;
    use \App\Components\Traits\Mailer\OrderTrait;
    use \App\Components\Traits\Mailer\ProductVariantTrait;
    
    public static function attachBeautymailSettings($data)
    {
        $viewSettings = config('beautymail.view');
        $viewSettings['logo']['path'] = str_replace(
            '%PUBLIC%',
            \Request::getSchemeAndHttpHost(),
            $viewSettings['logo']['path']
        );
        $data = array_merge(
            $data,
            $viewSettings,
            [
                'css' => implode(' ', config('beautymail.css'))
            ]
        );
        
        return $data;
    }
}
