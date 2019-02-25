<?php

namespace App\Models\KZ;

use DB;

class KZSize
{
    public static function getForArtPrints()
    {
        return DB::connection('mysql')
            ->table('cscart_aws_sizes')
            ->select('id', 'name')
            ->where('type', '=', 'art_print')
            ->get();
    }
    
    public static function getForSocks()
    {
        return DB::connection('mysql')
            ->table('cscart_aws_sizes')
            ->select('id', 'name')
            ->where('type', '=', 'socks')
            ->get();
    }
    
    public static function getByType($kzProductTypeCode)
    {
        return DB::connection('mysql')
            ->table('cscart_aws_sizes')
            ->select('id', 'name')
            ->where('type', '=', $kzProductTypeCode)
            ->get();
    }
}
