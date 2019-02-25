<?php

namespace App\Http\Controllers\Api\StoreApi;

use DB;
use Input;
use DingoRoute;
use Request as RequestFacade;
use Dingo\Api\Http\Request as DingoRequest;
use Illuminate\Http\Request;

use App\Transformers\Product\ProductEditingTransformer;

use App\Models\Product;
use App\Models\ProductVariant;

/**
 * Product Variants
 *
 * @Resource("Product Variants", uri="/products/{product}/variants", requestHeaders={
 *      "Authorization": "Bearer Ik6nj6HrKiJwVwgMfGOUPOz5Wa6ZuZns1kRli16sZC4YdigLtjJJlzDKdFZt"
 * })
*/
class ProductVariantsController extends StoreApiController
{   
    /**
     * Ignore product variant
     * 
     * @Post("/{variant}/ignore")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products/103/variants/1/ignore"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"ignored","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID"),
     *      @Parameter("variant", type="string", required=true, description="Product variant ID")
     * })
     */
    public function ignore(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('ignore', $variant);
        
        $variant->ignore();
        
        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }
        
    /**
     * Unignore product
     * 
     * @Post("/{variant}/unignore")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products/103/variants/1/unignore"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"active","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID"),
     *      @Parameter("variant", type="string", required=true, description="Product variant ID")
     * })
     */
    public function unignore(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('ignore', $variant);
        
        $variant->activate();
        
        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }
}
