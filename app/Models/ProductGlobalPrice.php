<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGlobalPrice extends Model
{
    use \Culpa\Traits\Blameable;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'aws_price_modifier';

    protected $fillable = [
       
    ];

    public function frontWhitePrice($id){
     return ProductGlobalPrice::where('side', 'F')->where('is_white_color','Y')->where('template_id',$id)->get();
    }

    public function frontColorPrice($id){
        return ProductGlobalPrice::where('side', 'F')->where('is_white_color','N')->where('template_id',$id)->get();
    }

    public function backWhitePrice($id){
        return ProductGlobalPrice::where('side', 'B')->where('is_white_color','Y')->where('template_id',$id)->get();
    }

    public function backColorPrice($id){
        return ProductGlobalPrice::where('side', 'B')->where('is_white_color','N')->where('template_id',$id)->get();
    }
}
