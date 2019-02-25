<?php

namespace App\Models\KZ;

use DB;

class KZProductType
{

	public static function getAllProductTypes()
	{
		return DB::connection('mysql')
            ->table('cscart_aws_product_types')
            ->select('*')

            // remove to fetch all
            ->whereIn('code', [
                // 'Reg Tees'
                    'A',

                // 'Wild Tees'
                    'wild_tee',


                // Embroidery
                    'head_wear',

                // Galoree socks
                    'socks',

                // phone cases
                    'phone_case',

                // art prints
                    // NOTE: doesn't work like this so far
                    //'art_print',

                // stickers
                    // NOTE: doesn't work like this so far
                    //'sticker'
            ])

            // PrintIO
            ->orWhere('category_id', 2)

            // Galloree
            ->orWhere('category_id', 3)

            ->get();
	}

	public static function getProductTypeByCategory($category)
	{
		return DB::connection('mysql')
            ->table('cscart_aws_product_types')
            ->select('*')
            ->where('description', $category)
            ->first();
	}
}
