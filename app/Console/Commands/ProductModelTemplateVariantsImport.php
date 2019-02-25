<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductModel;
use App\Models\ProductModelTemplate;
use App\Models\CatalogAttribute;
use App\Models\CatalogAttributeOption;
use App\Models\ProductGlobalPrice;
use App\Classes\Helper;
use DB;

class ProductModelTemplateVariantsImport extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-model-template-variants-import {--id=}';
    
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Product Template Variants.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $id = $this->option('id');

        $this->template = ProductModelTemplate::find($id);
        
        if (!$id || !$this->template) {
            throw new Exception('Product Model Template ID is required');
        }

        if (!$this->template->category) {
            throw new Exception('Cannot determine category for Template #'.$template->id);

        }

        $category = $this->template->category;

        $categoryName = $category->name;

        $i = 0;

        $this->template = ProductModelTemplate::find($id);
        
        $product_meta = $this->template->product_meta;
        
        $color = json_decode($product_meta)->color;
        
        $size  = json_decode($product_meta)->size;   
        
        $size_all  = CatalogAttributeOption::whereIn('id',$size)->get();
        
        $sides = array('F','B');
        
        foreach($color as $color_attribute=>$color_value){

            $color_name = ProductModelTemplate::getColorName($color_value)->value;
            
            for($j=0;$j<count($size_all); $j++){

                $size = $size_all[$j]->id;

                $color_price = array();

                foreach($sides as $globalside){

                    if($color_name == '#ffffff' || $color_name == '#fff'){

                        $Price_white = ProductGlobalPrice::where('is_white_color','Y')->where('template_id',$id)->where('side',$globalside)->where('size_id',$size)->get();
                        
                        $flatten = $Price_white->flatten();

                        $color_price_meta = $flatten->all();
        
                        $price =  $color_price_meta[0]->brand_print_price;
                        
                        if($price!='' && $price >= 0){
                            $price =  $color_price_meta[0]->brand_print_price;
                        }
                        else{
                            $price =  $color_price_meta[0]->customer_price;
                        }
                    }
                    else{
                        
                        $color_price = ProductGlobalPrice::where('is_white_color','N')->where('template_id',$id)->where('side',$globalside)->where('size_id',$size)->get();
                        
                        $flatten = $color_price->flatten();

                        $color_price_meta = $flatten->all();
                        
                        $price =  $color_price_meta[0]->customer_price;
                        
                        if($price!='' && $price >= 0){
                            $price =  $color_price_meta[0]->brand_print_price;
                        }
                        else{
                            $price =  $color_price_meta[0]->customer_price;
                        }

                       
                    }
                    if($color_price_meta[0]->side == 'F'){

                        $price_front =  $price;
                        
                    }
                    else{

                        $price_back = $price;
                        
                    } 
                }  
               
                $product_model = ProductModel::where('color',$color_value)->where('template_id',$id)->where('size',$size)->first();
                
                if(!$product_model){
                    
                    $product_model = new ProductModel();
                }

                $product_model->template_id = $id;

                $product_model->inventory_status = 'in_stock';

                $product_model->price = $price_front;

                $product_model->price_back = $price_back;

                $product_model->price_both  = $price_front + $price_back;

                $product_model->color = $color_value;

                $product_model->size  = $size; 

                if($product_model->save()){

                    $product_model_attribute_options = ProductModel::find($product_model->id);
                    
                    if($product_model_attribute_options->color!=''){
                        $product_model->optionsOfAttribute('color')->sync([$product_model_attribute_options->color,$product_model_attribute_options->size]);
                    }
                    
                }  

                
            }
                         
            $i++;
        } 
        \Artisan::call('cache:clear');
    }
}
