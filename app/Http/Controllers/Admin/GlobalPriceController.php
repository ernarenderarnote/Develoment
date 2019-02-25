<?php

namespace App\Http\Controllers\Admin;

use DataForm;
use Input;
use View;
use Exception;
use Log;
use Bugsnag;
use Redirect;
use Illuminate\Http\Request;

use App\Components\Shopify;
use App\Models\Product;
use App\Models\ProductDesignerFile;
use App\Models\ProductVariant;
use App\Models\FileAttachment;
use App\Models\ProductModel;
use App\Models\ProductModelTemplate;
use App\Models\CatalogAttributeOption;
use App\Models\ProductGlobalPrice;
use App\Http\Requests\Admin\Product\ProductSaveFormRequest;

class GlobalPriceController extends AdminController
{
 
    public function add(Request $request,$id){
    
        $data = ProductGlobalPrice::where('template_id',$id)->get();
        
        $attribute_options = ProductModelTemplate::find($id)->product_meta;

        if($attribute_options==''){
            flash()->error(trans('messages.please_select_color_and_size_first'));
            return Redirect::back();
        }

        foreach(json_decode($attribute_options) as $key=>$value){
        
            if($key=='size'){
                $size_attributes = CatalogAttributeOption::find($value)->pluck('name','id');
            }
            
        }
        if($attribute_options==''){
            flash()->error(trans('messages.please_select_color_and_size_first'));
            return Redirect::back();
        }
        if ($request->isMethod('post')) {
           
            foreach($request->side as $key=>$value){
                
                if($request->brand_print_price[$key]=='0'){
                    flash()->error(trans('messages.brand_print_price_not_defined'));
                    return Redirect::back();
                }

                if($request->color[$key] == 'white'){
                    $is_white = 'Y';
                }else{
                    $is_white = 'N';
                }
                
                if($request->side[$key] == 'front_side'){
                    $side = 'F';
                }else{
                    $side = 'B';
                }
                $ProductGlobal = ProductGlobalPrice::where('template_id',$id)->where('size_id',$request->size[$key])->where('side',$side)->where('is_white_color',$is_white)->first();
                
                if($ProductGlobal){
                    $ProductGlobalPrice = $ProductGlobal;
                    $message = 'updated';
                }
                else{
                    $ProductGlobalPrice = new ProductGlobalPrice();
                    $message = 'saved';
                }
                $ProductGlobalPrice->template_id = $id;
                
                $ProductGlobalPrice->size_id = $request->size[$key];
                
                $ProductGlobalPrice->customer_price = $request->customer_price[$key] ? $request->customer_price[$key] : '0.00' ;
                
                $ProductGlobalPrice->customer_print_price = $request->customer_print_price[$key] ? $request->customer_print_price[$key]:'0.00';
                
                $ProductGlobalPrice->brand_price = $request->brand_price[$key] ? $request->brand_price[$key] :'0.00';
                
                $ProductGlobalPrice->brand_print_price = $request->brand_print_price[$key] ? $request->brand_print_price[$key]:'0.00';
                
                $ProductGlobalPrice->is_white_color = $is_white;

                $ProductGlobalPrice->side = $side;

                $ProductGlobalPrice->save(); 
            } 
            
            flash()->success(trans('messages.'.$message));   
        }

        $model = new ProductGlobalPrice();
        return view('admin.pages.product-variant-options.show',['title' => trans('labels.variatns'),
        'subtitle' =>'Variant options',
        'size_attributes'=>$size_attributes,
        'model'=>$model,
        'template_id'=>$id]);
      
    }
    
    
}
