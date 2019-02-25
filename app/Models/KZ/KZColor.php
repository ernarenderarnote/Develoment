<?php

namespace App\Models\KZ;

use DB;

class KZColor
{
	
	public static function getGarmentColorsData($colorIds)
	{
		$colorsData = DB::connection('mysql')
            ->table('cscart_aws_colors')
            ->select('aws_colors_id as id', 'aws_colors_name as name', 'aws_colors_value as value')
            ->whereIn('aws_colors_id', $colorIds)
            ->groupBy('aws_colors_name')
            ->get();

        return $colorsData;
	}
	
    public static function getColors()
    {
        return DB::connection('mysql')
            ->table('cscart_aws_colors')
            ->select('aws_colors_id as id', 'aws_colors_name as name', 'aws_colors_value as value')
            ->groupBy('aws_colors_name')
            ->get();
    }
	
}
