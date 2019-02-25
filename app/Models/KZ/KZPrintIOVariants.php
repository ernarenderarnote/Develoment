<?php

namespace App\Models\KZ;

use DB;

class KZPrintIOVariants
{
    public static function getByProductTypeId($kzProductTypeId)
    {
        return DB::connection('mysql')
            ->table('cscart_aws_printio_product_type_options as options')
            ->select('options.id', 'sku', 'title', 'label')
            ->rightJoin('cscart_aws_printio_product_types as product_types', 'options.type_id', '=', 'product_types.id')
            ->where('product_type_id', '=', $kzProductTypeId)
            ->get();
    }
}
