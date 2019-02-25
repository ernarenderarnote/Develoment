<?php

namespace App\Widgets\Admin;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Product;
use App\Models\ProductModelTemplate;
use App\Models\SupportRequest;
use App\Models\Order;

class LeftNav extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('admin.widgets.left-nav', [
            'productsOnModeration' => Product::countOnModeration(),
            'productsAutoApproved' => Product::countAutoApproved(),
            'productModelTemplateWithoutSourceTemplates' => ProductModelTemplate::countWithoutSourceTemplates(),
            'productModelTemplateWithoutImages' => ProductModelTemplate::countWithoutImages(),
            'productModelTemplateWithoutOverlays' => ProductModelTemplate::countWithoutOverlays(),
            'productModelTemplateWithoutPrices' => ProductModelTemplate::countWithoutPrices(),
            'productModelTemplateComplete' => ProductModelTemplate::countComplete(),
            'productModelTemplateVisible' => ProductModelTemplate::visible()->complete()->count(),
            'orderAll' => Order::count(),
            'orderRefunds' => Order::countRefundRequests(),
            'orderWithoutShippingGroups' => Order::countWithoutShippingGroups(),
            'orderNotSentToKZAPI' => Order::countNotSentToKZAPI(),
        ]);
    }
}
