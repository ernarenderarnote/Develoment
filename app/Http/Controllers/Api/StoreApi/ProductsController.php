<?php

namespace App\Http\Controllers\Api\StoreApi;

use DB;
use Input;
use DingoRoute;
use Request as RequestFacade;
use Dingo\Api\Http\Request as DingoRequest;
use Illuminate\Http\Request;

use App\Http\Requests\Dashboard\Product\ProductSendToModerationFormRequest;
use App\Http\Controllers\Dashboard\Traits\Products\SendToModerationTrait;
use App\Http\Requests\Product\CreateProjectFormRequest;
use App\Http\Requests\Product\UpdateProjectFormRequest;
use App\Transformers\Product\ProductBriefTransformer;
use App\Transformers\Product\ProductWithVariantsTransformer;
use App\Transformers\Product\ProductFullTransformer;
use App\Transformers\Product\ProductEditingTransformer;
use App\Jobs\Product\ProductPushToStoreJob;
use App\Models\Product;

/**
 * Products
 *
 * @Resource("Products", uri="/products", requestHeaders={
 *      "Authorization": "Bearer Ik6nj6HrKiJwVwgMfGOUPOz5Wa6ZuZns1kRli16sZC4YdigLtjJJlzDKdFZt"
 * })
*/
class ProductsController extends StoreApiController
{
    use SendToModerationTrait;

    const STATE_ACTIVE = 'active';
    const STATE_PENDING = 'pending';
    const STATE_SYNCED = 'synced';
    const STATE_APPROVED = 'approved';
    const STATE_AVAILABLE_FOR_DIRECT_ORDER = 'available_for_direct_orders';

    /**
     * Product categories list
     *
     * @Get("/categories")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/categories"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"categories":{{"id":1,"name":"Root","slug":"root","isPrepaid":false,"prepaid_amount":null,"prepaidAmountMoney":{"amount":"0","currency":"USD"},"preview":null,"templates":{{"id":1,"name":"Racerback Tank Top","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"isPrepaid":false,"prepaid_amount":null,"prepaidAmountMoney":null,"product_title":"Racerback Tank Top Girl","product_description":"Racerback Tank Top Girl","preview":null,"image":"http://monetize-social.dev/system/App/Models/File/files/000/000/268/original/aa_rsa2329_women_fine_jersey_racerback_tank_front.png","imageBack":"http://monetize-social.dev/system/App/Models/File/files/000/000/269/original/aa_rsa2329_women_fine_jersey_racerback_tank_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":1,"name":"Tank-Top","slug":"tank_top","position":2,"preview":null,"isAllOverPrint":false,"isAllOverPrintOrSimilar":false,"garmentGroup":{"id":1,"name":"Women","slug":"women","position":2}}},{"id":83,"name":"Snap Back Baseball Cap","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"isPrepaid":true,"prepaid_amount":"25.00","prepaidAmountMoney":{"amount":"2500","currency":"USD"},"product_title":"Snap Back Baseball Cap","product_description":"Snap Back Baseball Cap","preview":null,"image":null,"imageBack":null,"backPrintCanBeAddedOnItsOwn":true,"garment":{"id":15,"name":"Headwear","slug":"headwear","position":5,"preview":null,"isAllOverPrint":false,"isAllOverPrintOrSimilar":true,"garmentGroup":{"id":3,"name":"Unisex / Men","slug":"unisex_men","position":1}}}}}},"attributes":{{"id":1,"name":"Size","value":"size","option_ids":{2,3,4,5,9,10,27,69,70,71,72,75,143,144,145,146,178,179},"options":{{"id":2,"name":"XS","value":"xs"},{"id":3,"name":"S","value":"s"},{"id":4,"name":"M","value":"m"},{"id":5,"name":"L","value":"l"},{"id":9,"name":"XL","value":"xl"},{"id":10,"name":"2XL","value":"2xl"},{"id":27,"name":"3XL","value":"3xl"},{"id":69,"name":"6 month","value":"6 month"},{"id":70,"name":"12 month","value":"12 month"},{"id":71,"name":"18 month","value":"18 month"},{"id":72,"name":"NB","value":"nb"},{"id":75,"name":"24 month","value":"24 month"},{"id":143,"name":"2yr","value":"2yr"},{"id":144,"name":"4yr","value":"4yr"},{"id":145,"name":"OS","value":"os"},{"id":146,"name":"3 month","value":"3 month"},{"id":178,"name":"S/M","value":"s/m"},{"id":179,"name":"L/XL","value":"l/xl"}}},{"id":2,"name":"Color","value":"color","option_ids":{1,6,7,8,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,73,74,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,177,180,181,182,183,184,185},"options":{{"id":1,"name":"Black","value":"#302f2f"},{"id":6,"name":"Navy","value":"#21217a"},{"id":7,"name":"White","value":"#ffffff"},{"id":8,"name":"Asphalt","value":"#60615b"},{"id":11,"name":"Brown","value":"#483131"},{"id":12,"name":"Charcoal Heather","value":"#4b4d52"},{"id":13,"name":"Heather Grey","value":"#b0b0b2"},{"id":14,"name":"Kelly Green","value":"#278835"},{"id":15,"name":"Oatmeal","value":"#e8e8e8"},{"id":16,"name":"Royal","value":"#1541aa"},{"id":17,"name":"Athletic Heather","value":"#b8b3af"},{"id":18,"name":"Baby Blue","value":"#a6b8d0"},{"id":19,"name":"CHOCOLATE","value":"#47342b"},{"id":20,"name":"Leaf","value":"#437c52"},{"id":21,"name":"Maroon","value":"#6c1f3f"},{"id":22,"name":"Ocean Blue","value":"#8ba5ca"},{"id":23,"name":"plum","value":"#5a224a"},{"id":24,"name":"Red","value":"#c01c34"},{"id":25,"name":"True Royal","value":"#394694"},{"id":26,"name":"Saturn Swirl","value":"#71c2d9"},{"id":28,"name":"White/Asphalt","value":"#54585a"},{"id":29,"name":"White/Black","value":"#353434"},{"id":30,"name":"White/Kelly Green","value":"#007a53"},{"id":31,"name":"White/Navy","value":"#2f304f"},{"id":32,"name":"White/Red","value":"#d60024"},{"id":33,"name":"White/True Royal","value":"#385e9d"},{"id":34,"name":"Banana Cream","value":"#f5e09f"},{"id":35,"name":"Dark Grey","value":"#a9a9a9"},{"id":36,"name":"Envy","value":"#5abd8f"},{"id":37,"name":"Hot Pink","value":"#ff69b4"},{"id":38,"name":"Light Heather Grey","value":"#bebbbb"},{"id":39,"name":"Neon Heather Green","value":"#c0f356"},{"id":40,"name":"Neon Heather Orange","value":"#ff850e"},{"id":41,"name":"Neon Heather Pink","value":"#fe0068"},{"id":42,"name":"Neon Yellow","value":"#dee24f"},{"id":43,"name":"Purple Rush","value":"#5d1399"},{"id":44,"name":"Turquoise","value":"#01a4c5"},{"id":45,"name":"BLOSSOM","value":"#ee9ab8"},{"id":46,"name":"BLUE SAGE","value":"#5b9c70"},{"id":47,"name":"Charcoal","value":"#586369"},{"id":48,"name":"IRIS","value":"#583e7b"},{"id":49,"name":"METEORITE","value":"#645c53"},{"id":50,"name":"SKY","value":"#d6e4e5"},{"id":51,"name":"WASABI","value":"#afd77f"},{"id":52,"name":"Truffle","value":"#6c333a"},{"id":53,"name":"Eggplant","value":"#542c5d"},{"id":54,"name":"Olive","value":"#2e4227"},{"id":55,"name":"Slate","value":"#9d9cae"},{"id":56,"name":"BLACK","value":"#353434"},{"id":57,"name":"Army","value":"#3e3e33"},{"id":58,"name":"Berry","value":"#c3346a"},{"id":59,"name":"Gold","value":"#fead28"},{"id":60,"name":"Orange","value":"#da662d"},{"id":61,"name":"Silver","value":"#ccd1d4"},{"id":62,"name":"Soft Cream","value":"#f6f2e9"},{"id":63,"name":"Team Purple","value":"#532c61"},{"id":64,"name":"Neon Rainbow","value":"#d23c7f"},{"id":65,"name":"CITY GREEN","value":"#3e4827"},{"id":66,"name":"Green Apple","value":"#509e2f"},{"id":67,"name":"INDEPENDENCE RED","value":"#862633"},{"id":68,"name":"Royal Blue","value":"#1d4f91"},{"id":73,"name":"Pink","value":"#fbb6c8"},{"id":74,"name":"Apple","value":"#6cc24a"},{"id":76,"name":"Butter","value":"#fbd872"},{"id":77,"name":"Light Blue","value":"#a4c8e1"},{"id":78,"name":"Dark Grey Heather","value":"#444444"},{"id":79,"name":"Heather Gray (90/10)","value":"#b1b3b6"},{"id":80,"name":"Heavy Metal","value":"#2e382f"},{"id":81,"name":"Indigo","value":"#465866"},{"id":82,"name":"Sand","value":"#cfc6bd"},{"id":83,"name":"Tahiti Blue","value":"#02acc6"},{"id":84,"name":"Aqua","value":"#4a9ccb"},{"id":85,"name":"Ash Gray Apricot","value":"#ffb3ab"},{"id":86,"name":"Ash Gray Sea Foam","value":"#bfcec2"},{"id":87,"name":"ASH GREY APRICOT","value":"#d19b8e"},{"id":88,"name":"ASH GRY SEA FOAM","value":"#989c8d"},{"id":89,"name":"Ash White Stripe","value":"#dbd8e1"},{"id":90,"name":"BUTTER","value":"#fae2a2"},{"id":91,"name":"Camel","value":"#d5ae77"},{"id":92,"name":"Coral","value":"#fc9a6b"},{"id":93,"name":"Cranberry","value":"#ba004d"},{"id":94,"name":"Creme","value":"#f9e1b7"},{"id":95,"name":"Forest","value":"#446463"},{"id":96,"name":"Fuchsia","value":"#ea60a7"},{"id":97,"name":"Heather Gray White Stripe","value":"#b7b8b9"},{"id":98,"name":"HTHR GRY WHT STP","value":"#dcdfe6"},{"id":99,"name":"Lapis","value":"#233a7e"},{"id":100,"name":"Lemon","value":"#ffff99"},{"id":101,"name":"Lieutenant ","value":"#7e876d"},{"id":102,"name":"Light Aqua","value":"#82d8d5"},{"id":103,"name":"Light Pink","value":"#fde4e9"},{"id":104,"name":"Lime","value":"#64c425"},{"id":105,"name":"Mauve","value":"#ccb4c4"},{"id":106,"name":"Mint","value":"#4cc1a1"},{"id":107,"name":"New Silver","value":"#c5c5c5"},{"id":108,"name":"PURPLE","value":"#3b4883"},{"id":109,"name":"RASPBERRY","value":"#b8407a"},{"id":110,"name":"SEAFOAM","value":"#659a9a"},{"id":111,"name":"Summer Peach","value":"#f4c3cb"},{"id":112,"name":"Sunshine","value":"#ffe15e"},{"id":113,"name":"Teal","value":"#01a59e"},{"id":114,"name":"Ash Grey","value":"#c8c9c7"},{"id":115,"name":"Cardinal Red","value":"#8d2838"},{"id":116,"name":"CAROLINA BLUE","value":"#7ba4db"},{"id":117,"name":"Forest Green","value":"#273b32"},{"id":118,"name":"SPORT GREY","value":"#97999b"},{"id":119,"name":"Tennessee Orange","value":"#ea961c"},{"id":120,"name":"Ash","value":"#e8e8e8"},{"id":121,"name":"Cardinal","value":"#890e23"},{"id":122,"name":"Deep Heather","value":"#908b8c"},{"id":123,"name":"Midnight Navy","value":"#384039"},{"id":124,"name":"Cool Blue","value":"#335994"},{"id":125,"name":"Cream","value":"#f2e3bb"},{"id":126,"name":"Dark Chocolate","value":"#3c2516"},{"id":127,"name":"Light Gray","value":"#b2b3ad"},{"id":128,"name":"Light Olive","value":"#898d6d"},{"id":129,"name":"Military Green","value":"#414728"},{"id":130,"name":"NATURAL","value":"#dcd6c1"},{"id":131,"name":"Warm Gray","value":"#929b96"},{"id":132,"name":"GRASS","value":"#276843"},{"id":133,"name":"CHARITY PINK","value":"#f8a3bc"},{"id":134,"name":"MOSS","value":"#41473d"},{"id":135,"name":"SMOKE","value":"#3d3935"},{"id":136,"name":"VIOLET","value":"#6861a5"},{"id":137,"name":"Canvas Red","value":"#bd363a"},{"id":138,"name":"Kelly","value":"#018264"},{"id":139,"name":"Neon Blue","value":"#0076bc"},{"id":140,"name":"Neon Green","value":"#26aa3d"},{"id":141,"name":"Neon Pink","value":"#f6849e"},{"id":142,"name":"Steel Blue","value":"#58748a"},{"id":177,"name":"Heather","value":"#b3bccc"},{"id":180,"name":"Dark Navy","value":"#212342"},{"id":181,"name":"Gray","value":"#b1b3b3"},{"id":182,"name":"BLACK/BLACK","value":"#353434"},{"id":183,"name":"GREY/BLACK","value":"#696969"},{"id":184,"name":"OLIVE/BLACK","value":"#604c11"},{"id":185,"name":"White/Black","value":"#ffffff"}}},{"id":3,"name":"Variant","value":"variant","option_ids":{147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176},"options":{{"id":147,"name":"16 x 16","value":"ThrowPillow_Zipper_16x16"},{"id":148,"name":"18 x 18","value":"ThrowPillow_Zipper_18x18"},{"id":149,"name":"20 x 20","value":"ThrowPillow_Zipper_20x20"},{"id":150,"name":"26 x 26","value":"ThrowPillow_Zipper_26x26"},{"id":151,"name":"71 x 94","value":"ShowerCurtain_71x94"},{"id":152,"name":"71 x 74","value":"ShowerCurtain_71x74"},{"id":153,"name":"9 x 9","value":"ToteBag-9x9"},{"id":154,"name":"13 x 13","value":"ToteBag-13x13"},{"id":155,"name":"16 x 16","value":"ToteBag-16x16"},{"id":156,"name":"13x13x13","value":"Ottoman-13x13x13"},{"id":157,"name":"18x18x18","value":"Ottoman-18x18x18"},{"id":158,"name":"11oz","value":"Mug-11oz"},{"id":159,"name":"Size: 10x10","value":"HandTowel-10x10"},{"id":160,"name":"Size: 15x24","value":"HandTowel-15x24"},{"id":161,"name":"King","value":"DuvetCover-K-Luxe-88x104-WhiteBack"},{"id":162,"name":"Queen","value":"DuvetCover-Q-Luxe-88x88-WhiteBack"},{"id":163,"name":"Twin","value":"DuvetCover-TW-Luxe-68x88-WhiteBack"},{"id":164,"name":"FlEECE 18 x 28","value":"DogBed-Fleece-18x28"},{"id":165,"name":"FlEECE 30 x 40","value":"DogBed-Fleece-30x40"},{"id":166,"name":"FlEECE 40 x 50","value":"DogBed-Fleece-40x50"},{"id":167,"name":"OUTDOOR 18 x 28","value":"Outdoor Dog Bed 18DogBed-Outdoor-18x28"},{"id":168,"name":"OUTDOOR 30 x 40","value":"DogBed-Outdoor-30x40"},{"id":169,"name":"OUTDOOR 40 x 50","value":"DogBed-Outdoor-40x50"},{"id":170,"name":"30 x 60","value":"BathTowel-30x60"},{"id":171,"name":"36 x 72","value":"BeachTowel-36x72"},{"id":172,"name":"OS","value":"EverythingBag"},{"id":173,"name":"title","value":"WovenBlanket_50x60"},{"id":174,"name":"","value":"WovenBlanket_60x80"},{"id":175,"name":"Size: 50x60","value":"FleeceBlanket_50x60"},{"id":176,"name":"Size: 60x80","value":"FleeceBlanket_60x80"}}}}}}),
     * })
     */
    public function getCategories()
    {
        return app('App\Http\Controllers\Dashboard\ProductsController')
            ->getCategories();
    }

    /**
     * Get product model template by id
     *
     * @Get("/template/{template_id}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/template/83"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"template":{"id":83,"name":"Snap Back Baseball Cap","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"isPrepaid":true,"prepaid_amount":"25.00","prepaidAmountMoney":{"amount":"2500","currency":"USD"},"product_title":"Snap Back Baseball Cap","product_description":"Snap Back Baseball Cap","preview":null,"image":null,"imageBack":null,"backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":16,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":16,"attribute_id":2}}},"optionsTree":{"145":{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"},"children":{"1":{"id":1,"name":"Black","value":"#302f2f","kz_option_id":42,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2626},"35":{"id":35,"name":"Dark Grey","value":"#a9a9a9","kz_option_id":249,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2627},"177":{"id":177,"name":"Heather","value":"#b3bccc","kz_option_id":312,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2628},"6":{"id":6,"name":"Navy","value":"#21217a","kz_option_id":89,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2629},"24":{"id":24,"name":"Red","value":"#c01c34","kz_option_id":65,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2630},"16":{"id":16,"name":"Royal","value":"#1541aa","kz_option_id":244,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2631},"7":{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"},"children":{},"model_id":2632}}}},"models":{{"id":2590,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":1,"name":"Black","value":"#302f2f","kz_option_id":42,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2591,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":35,"name":"Dark Grey","value":"#a9a9a9","kz_option_id":249,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2592,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":177,"name":"Heather","value":"#b3bccc","kz_option_id":312,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2593,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":6,"name":"Navy","value":"#21217a","kz_option_id":89,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2594,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":24,"name":"Red","value":"#c01c34","kz_option_id":65,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2595,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":16,"name":"Royal","value":"#1541aa","kz_option_id":244,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2596,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2626,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":1,"name":"Black","value":"#302f2f","kz_option_id":42,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2627,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":35,"name":"Dark Grey","value":"#a9a9a9","kz_option_id":249,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2628,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":177,"name":"Heather","value":"#b3bccc","kz_option_id":312,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2629,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":6,"name":"Navy","value":"#21217a","kz_option_id":89,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2630,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":24,"name":"Red","value":"#c01c34","kz_option_id":65,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2631,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":16,"name":"Royal","value":"#1541aa","kz_option_id":244,"attribute":{"id":2,"name":"Color","value":"color"}}}},{"id":2632,"price":"13.52","priceMoney":{"amount":"1352","currency":"USD"},"frontPrice":"13.52","frontPriceMoney":{"amount":"1352","currency":"USD"},"backPrice":"13.52","backPriceMoney":{"amount":"1352","currency":"USD"},"bothSidesPrice":"13.52","bothSidesPriceMoney":{"amount":"1352","currency":"USD"},"options":{{"id":145,"name":"OS","value":"os","kz_option_id":8,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"category":{"id":16,"name":"Headwear","slug":"headwear","isPrepaid":true,"prepaid_amount":"25.00","prepaidAmountMoney":{"amount":"2500","currency":"USD"},"preview":null},"garment":{"id":15,"name":"Headwear","slug":"headwear","position":5,"preview":null,"isAllOverPrint":false,"isAllOverPrintOrSimilar":true,"garmentGroup":{"id":3,"name":"Unisex \/ Men","slug":"unisex_men","position":1}}}}})
     * })
     * @Parameters({
     *      @Parameter("template_id", type="integer", required=true, description="Product model template ID"),
     * })
     */
    public function getProductModelTemplate(Request $request, $template_id)
    {
        return app('App\Http\Controllers\Dashboard\ProductsController')
            ->getProductModelTemplate($request, $template_id);
    }

    /**
     * Products list
     *
     * Get current store's products. Could be used with status filter
     *
     * @Get("/?page={page}&state={state}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"products":{"data":{{"id":31,"name":"Crop Tank Top Girl","status":"active","variants":{"data":{{"id":50,"name":"Crop Tank Top Girl - S White","status":"active","printPriceMoney":{"amount":"0","currency":"USD"},"printPrice":"0.00","model":{"id":463,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{"data":{{"id":27,"name":"3XL","value":"3xl","kz_option_id":7,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":64,"name":"Neon Rainbow","value":"#d23c7f","kz_option_id":310,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"mockups":{"data":{{"id":266,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/medium\/variant-mockup-50.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/original\/variant-mockup-50.jpg"}}}},{"id":51,"name":"S \/ White","status":"active","printPriceMoney":{"amount":"0","currency":"USD"},"printPrice":"0.00","mockups":{"data":{}}}}},"mockupPreview":{"id":266,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/medium\/variant-mockup-50.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/original\/variant-mockup-50.jpg","dimensions":{"width":427,"height":617}}}},"meta":{"pagination":{"total":26,"count":1,"per_page":1,"current_page":1,"total_pages":26,"links":{"next":"http:\/\/monetize-social.dev\/api\/store-api\/v1\/products?page=2"}}}}}})
     * })
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="Pagination page", default=1),
     *      @Parameter("state", type="string", required=false, description="Product status filter", default="synced", members={
     *          @Member(value="synced", description="Products synced with extarnal store, active and ignored"),
     *          @Member(value="available_for_direct_orders", description="Products available for Direct Orders (approved by moderator)"),
     *          @Member(value="pending", description="Products in queue for sync or drafts")
     *      })
     * })
     */
    public function index(Request $request)
    {
        $state = $request->get('state');

        $products = null;
        switch($state) {
            case static::STATE_PENDING:
                $products = $this->getStore()
                    ->vendorProductsPending()
                    ->paginate($this->perPage);
                break;

            case static::STATE_AVAILABLE_FOR_DIRECT_ORDER:
                $products = $this->getStore()
                    ->vendorProductsAllowedDirectOrder()
                    ->paginate($this->perPage);
                break;

            case static::STATE_SYNCED:
            default:
                $products = $this->getStore()
                    ->vendorProductsSynced()
                    ->paginate($this->perPage);
        }

        return response()->api([
            'products' => $this->paginator($products, new ProductWithVariantsTransformer)
        ]);
    }

    /**
     * Add product
     *
     * Add product and send it on moderation/to external store
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |product_model_template_id | integer, required, exists:product_model_templates,id|
       |product_title | string, required, max:255|
       |product_description | string, required|
       |model_id.* | integer, required, exists:product_models,id|
       |retail_price.* | numeric, required|
       |existing_file_id | integer, exists:files,id|
       |existing_source_file_id | integer, exists:files,id|
       |existing_file_back_id | integer, exists:files,id|
       |existing_source_file_back_id | integer, exists:files,id|
       |print_coordinates | json, required|
       |canvas_size | json, required|
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({
                "product_model_template_id": 13,
                "print_coordinates": {
                    "left": 148.97826086957,
                    "top": 235.07896344568,
                    "width": 89.04347826087,
                    "height": 89.04347826087
                },
                "canvas_size": {
                    "width": 387,
                    "height": 559.20140515222
                },
                "print_coordinates_back": {
                    "left": 205.41304347826,
                    "top": 246.20604514904,
                    "width": 100.17391304348,
                    "height": 86.782608695652
                },
                "canvas_size_back": {
                    "width": 387,
                    "height": 559.20140515222
                },
                "product_title": "Product Title",
                "product_description": "Product Description",
                "retail_price": {
                    "465": 1,
                    "466": 2,
                    "469": 3,
                    "470": 4
                },
                "existing_file_id": 128,
                "existing_source_file_id": "",
                "existing_file_back_id": 127,
                "existing_source_file_back_id": "",
                "model_id": {470},
                "product_id": ""
     *      }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"active","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(422, body={"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"store_id":{"The store id field is required."},"product_model_template_id":{"The product model template id field is required."},"product_title":{"The product title field is required."},"product_description":{"The product description field is required."},"print_coordinates":{"The print coordinates field is required."},"canvas_size":{"The canvas size field is required."}},"status":422,"data":{}})
     * })
     */
    public function store(ProductSendToModerationFormRequest $request)
    {
        $newData = [
            'canvas_size' => json_encode($request->get('canvas_size')),
            'canvas_size_back' => json_encode($request->get('canvas_size_back')),
            'print_coordinates' => json_encode($request->get('print_coordinates')),
            'print_coordinates_back' => json_encode($request->get('print_coordinates_back')),
            'store_id' => $this->getStore()->id
        ];
        Input::merge($newData);
        $request->merge($newData);
        $request->request->replace(array_merge($request->request->all(), $newData));

        return $this->createAndSendToModeration($request);
    }

        /**
         * Override for createAndSendToModeration()
         */
        public function productSendToModeration($product)
        {
            return response()->api([
                'product' => $this->item($product, new ProductEditingTransformer)
            ]);
        }

    /**
     * Push product to store
     *
     * Pushes product to the external store
     *
     * @Post("/{product}/push-to-store")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({}),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"queued_for_sync","moderation_status":"approved","moderationStatusName":"Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function pushToStore($product_id)
    {
        $product = Product::find($product_id);
        $this->authorize('push_to_store', $product);

        if ($product->isQueuedForSync()) {
            return $this->response->error(trans('messages.product_already_queued_to_be_pushed_to_store'), 400);
        }

        dispatch(new ProductPushToStoreJob($product));
        $product->queuedForSync();

        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }

    /**
     * Get product
     *
     * @Get("/{product}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products/103"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"active","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function show(Product $product)
    {
        $this->authorize('show', $product);
        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }

    /**
     * Update product
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |_method | string, required | put | Override HTTP method to use PUT |
       |product_model_template_id | integer, required, exists:product_model_templates,id|
       |product_title | string, required, max:255|
       |product_description | string, required|
       |model_id.* | integer, required, exists:product_models,id|
       |retail_price.* | numeric, required|
       |existing_file_id | integer, exists:files,id|
       |existing_source_file_id | integer, exists:files,id|
       |existing_file_back_id | integer, exists:files,id|
       |existing_source_file_back_id | integer, exists:files,id|
       |print_coordinates | json, required|
       |canvas_size | json, required|
     *
     * @Put("/{product}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({
                "_method": "put",
                "product_model_template_id": 13,
                "print_coordinates": {
                    "left": 148.97826086957,
                    "top": 235.07896344568,
                    "width": 89.04347826087,
                    "height": 89.04347826087
                },
                "canvas_size": {
                    "width": 387,
                    "height": 559.20140515222
                },
                "print_coordinates_back": {
                    "left": 205.41304347826,
                    "top": 246.20604514904,
                    "width": 100.17391304348,
                    "height": 86.782608695652
                },
                "canvas_size_back": {
                    "width": 387,
                    "height": 559.20140515222
                },
                "product_title": "Product Title",
                "product_description": "Product Description",
                "retail_price": {
                    "465": 1,
                    "466": 2,
                    "469": 3,
                    "470": 4
                },
                "existing_file_id": 128,
                "existing_source_file_id": "",
                "existing_file_back_id": 127,
                "existing_source_file_back_id": "",
                "model_id": {465, 466, 469, 470}
            }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"draft","moderation_status":"on_moderation","moderationStatusName":"In moderation","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(422, body={"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"store_id":{"The store id field is required."},"product_model_template_id":{"The product model template id field is required."},"product_title":{"The product title field is required."},"product_description":{"The product description field is required."},"print_coordinates":{"The print coordinates field is required."},"canvas_size":{"The canvas size field is required."}},"status":422,"data":{}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function update(ProductSendToModerationFormRequest $request, Product $product)
    {
        $this->authorize('edit', $product);

        $newData = [
            'canvas_size' => json_encode($request->get('canvas_size')),
            'canvas_size_back' => json_encode($request->get('canvas_size_back')),
            'print_coordinates' => json_encode($request->get('print_coordinates')),
            'print_coordinates_back' => json_encode($request->get('print_coordinates_back')),
            'store_id' => $this->getStore()->id,
            'product_id' => $product->id
        ];
        Input::merge($newData);
        $request->merge($newData);
        $request->request->replace(array_merge($request->request->all(), $newData));

        return $this->createAndSendToModeration($request);
    }

    /**
     * Ignore product
     *
     * @Post("/{product}/ignore")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products/103/ignore"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"ignored","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function ignore(Request $request, Product $product)
    {
        $this->authorize('ignore', $product);

        $product->ignore();

        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }

    /**
     * Unignore product
     *
     * @Post("/{product}/unignore")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/products/103/unignore"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"product":{"id":"103","name":"Product Title","status":"active","moderation_status":"auto_approved","moderationStatusName":"Auto Approved","moderation_status_comment":"","meta":{"id":9752328778,"title":"Product Title","body_html":"Product Description","vendor":"mntz","product_type":"REG TEE","created_at":"2016-12-23T09:46:45-05:00","handle":"product-title-4","updated_at":"2016-12-23T09:46:46-05:00","published_at":"2016-12-23T09:46:45-05:00","template_suffix":null,"published_scope":"global","tags":"","variants":{{"id":35226475530,"product_id":9752328778,"title":"M \/ White","price":"4.00","sku":"","position":1,"grams":600,"inventory_policy":"deny","compare_at_price":null,"fulfillment_service":"manual","inventory_management":null,"option1":"M","option2":"White","option3":null,"created_at":"2016-12-23T09:46:45-05:00","updated_at":"2016-12-23T09:46:46-05:00","taxable":true,"barcode":null,"image_id":null,"inventory_quantity":1,"weight":0.6,"weight_unit":"kg","old_inventory_quantity":1,"requires_shipping":true}},"options":{{"id":11817950346,"product_id":9752328778,"name":"Size","position":1,"values":{"M"}},{"id":11817950410,"product_id":9752328778,"name":"Color","position":2,"values":{"White"}}},"images":{},"image":null},"canvas_meta":{"printCoordinates":{"left":148.97826086957,"top":235.07896344568,"width":89.04347826087,"height":89.04347826087},"clientCanvasSize":{"width":387,"height":559.20140515222},"printCoordinatesBack":{"left":205.41304347826,"top":246.20604514904,"width":100.17391304348,"height":86.782608695652},"clientCanvasSizeBack":{"width":387,"height":559.20140515222}},"retailPrices":{"470":"4.00"},"models":{{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}}}},"clientFiles":{{"id":79,"design_location":"front_chest","designerFiles":{},"mockup":{"id":467,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/medium\/variant-mockup-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/467\/original\/variant-mockup-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":128,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}}},{"id":80,"design_location":"back","designerFiles":{},"mockup":{"id":468,"type":"print_file_mockup_back","typeName":"Print file mockup back","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/medium\/variant-mockup-back-132.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/468\/original\/variant-mockup-back-132.jpg","dimensions":{"width":617,"height":617}},"printFile":{"id":127,"type":"print_file","typeName":"Print File","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function unignore(Request $request, Product $product)
    {
        $this->authorize('ignore', $product);

        $product->activate();

        return response()->api([
            'product' => $this->item($product, new ProductEditingTransformer)
        ]);
    }

    /**
     * Delete product
     *
     * **Body Attributes:**
     *
     * | name | type | value | description |
       | --- | --- | --- | --- |
       |_method | string, required | delete | Override HTTP method to use DELETE |
     *
     * @Delete("/{product}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"_method": "delete"}),
     *      @Response(200, body={"isError": false,"message": "","status": 200}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("product", type="string", required=true, description="Product ID")
     * })
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        DB::beginTransaction();

        foreach ($product->variants as $variant) {
            $variant->delete();
        }
        $product->delete();

        DB::commit();

        return response()->api([]);
    }
}
