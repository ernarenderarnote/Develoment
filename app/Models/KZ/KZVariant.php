<?php

namespace App\Models\KZ;

use DB;

/**
 * KZ Variants, has no relation to the MNTZ product variant entity
 */
class KZVariant
{
    protected static $garmentGendersMap = [
        'U' => 'Unisex',
        'M' => 'Guy',
        'G' => 'Girl',
        'Y' => 'Youth',
        'I' => 'Infant'
    ];

    public static function getGarments($includePrinters = false, $excludePrinters = false)
    {
        $garments = [];
        $garmentsOptionId = 119;

        if ($includePrinters || $excludePrinters) {

            if ($includePrinters) {
                $operator = 'whereIn';
                $printersValue = $includePrinters;
            } else {
                $operator = 'whereNotIn';
                $printersValue = $excludePrinters;
            }

            $garments = DB::connection('mysql')
                ->table('cscart_product_option_variants as variants')
                ->select('variants.variant_id', 'variant_name', 'weight_modifier as weight', 'sku', 'garments_genders.gender', 'sizes', 'color_ids', 'printer')
                ->leftJoin('cscart_product_option_variants_descriptions as variants_descriptions', 'variants.variant_id', '=', 'variants_descriptions.variant_id')
                ->leftJoin('cscart_aws_garment_gender as garments_genders', 'variants.variant_id', '=', 'garments_genders.variant_id')
                ->leftJoin('cscart_aws_garment_colors as colors', 'variants.variant_id', '=', 'colors.variant_id')
                ->leftJoin('cscart_aws_garment_sku as sku', 'variants.variant_id', '=', 'sku.variant_id')
                ->where('option_id', '=', $garmentsOptionId)
                ->where('status', '=', 'A')
                ->where('variant_name', '<>', 'Custom Socks')
                ->$operator('printer', $printersValue)
                ->orderBy('gender')
                ->get();
        }

        return $garments;
    }

    public static function getGarmentBySKU($includePrinters = false, $excludePrinters = false, $sku)
    {
        $garmentsOptionId = 119;

        if ($includePrinters) {
            $operator = 'whereIn';
            $printersValue = $includePrinters;
        } else {
            $operator = 'whereNotIn';
            $printersValue = $excludePrinters;
        }

        $garment = DB::connection('mysql')
            ->table('cscart_product_option_variants as variants')
            ->select('variants.variant_id', 'variant_name', 'weight_modifier as weight', 'sku', 'garments_genders.gender', 'sizes', 'color_ids', 'printer')
            ->leftJoin('cscart_product_option_variants_descriptions as variants_descriptions', 'variants.variant_id', '=', 'variants_descriptions.variant_id')
            ->leftJoin('cscart_aws_garment_gender as garments_genders', 'variants.variant_id', '=', 'garments_genders.variant_id')
            ->leftJoin('cscart_aws_garment_colors as colors', 'variants.variant_id', '=', 'colors.variant_id')
            ->leftJoin('cscart_aws_garment_sku as sku', 'variants.variant_id', '=', 'sku.variant_id')
            ->where('option_id', '=', $garmentsOptionId)
            ->where('status', '=', 'A')
            ->where('variant_name', '<>', 'Custom Socks')
            ->$operator('printer', $printersValue)
            ->where('sku', $sku)
            ->first();

        return $garment;
    }

    public static function getGarmentIdByNameAndSku($name, $sku)
    {
        $garmentsOptionId = 119;

        $garmentGendersMapFlipped = array_flip(static::$garmentGendersMap);

        $gender = null;
        if (stristr($name, 'guy')) {
            $gender = $garmentGendersMapFlipped['Guy'];
        }
        else if (stristr($name, 'girl')) {
            $gender = $garmentGendersMapFlipped['Girl'];
        }
        else if (stristr($name, 'youth')) {
            $gender = $garmentGendersMapFlipped['Youth'];
        }
        else if (stristr($name, 'infant')) {
            $gender = $garmentGendersMapFlipped['Infant'];
        }
        else if (stristr($name, 'unisex')) {
            $gender = $garmentGendersMapFlipped['Unisex'];
        }

        $name = trim(str_ireplace(static::$garmentGendersMap, '', $name));

        $garment = DB::connection('mysql')
            ->table('cscart_product_option_variants as variants')
            ->select('variants.variant_id', 'variant_name')
            ->leftJoin('cscart_product_option_variants_descriptions as variants_descriptions', 'variants.variant_id', '=', 'variants_descriptions.variant_id')
            ->leftJoin('cscart_aws_garment_sku as sku', 'variants.variant_id', '=', 'sku.variant_id')
            ->leftJoin('cscart_aws_garment_gender as garments_genders', 'variants.variant_id', '=', 'garments_genders.variant_id')
            ->where(function($q) use($name, $gender, $sku) { $q
                ->where(function($q) use($name) { $q
                    ->where('variants_descriptions.variant_name', '=', $name)
                    ->orWhere('variants_descriptions.variant_title', '=', $name);
                })
                ->orWhere(function($q) use($gender, $sku) { $q
                    ->where('sku', $sku);

                    if ($gender) {
                        $q->where('garments_genders.gender', '=', $gender);
                    }
                });

            })
            ->where('status', '=', 'A')
            ->first();

        return $garment;
    }

    public static function getGarmentName($garment)
    {
        return $garment->variant_name . " " . static::$garmentGendersMap[$garment->gender];
    }

    public static function getGarmentMaskUrl($garment_variant_id, $image_type = 'variant_image')
    {
        $image = DB::connection('mysql')
            ->table('cscart_images')
            ->select('cscart_images.image_id as image_id', 'image_path')
            ->join('cscart_images_links', 'cscart_images_links.image_id', '=', 'cscart_images.image_id')
            ->where('cscart_images_links.object_id', '=', $garment_variant_id)
            ->where('object_type', '=', $image_type)
            ->first();

        $url = ($image) ? 'http://kottonzoo.com/images/' . $image_type . '/' . floor($image->image_id / 1000) . '/' . $image->image_path : false;

        return $url;
    }

    public static function getPhoneCases()
    {
        $phoneCasesOptionId = 171;

        return DB::connection('mysql')
            ->table('cscart_product_option_variants as variants')
            ->select('variants.variant_id', 'variant_name as name', 'printer')
            ->leftJoin('cscart_product_option_variants_descriptions as variants_descriptions', 'variants.variant_id', '=', 'variants_descriptions.variant_id')
            ->where('option_id', '=', $phoneCasesOptionId)
            ->get();
    }

    public static function getShoesVariants()
    {
        $shoesOptionId = 176;

        return DB::connection('mysql')
            ->table('cscart_product_option_variants as variants')
            ->select('variants.variant_id', 'variant_name')
            ->leftJoin('cscart_product_option_variants_descriptions as variants_descriptions', 'variants.variant_id', '=', 'variants_descriptions.variant_id')
            ->where('option_id', '=', $shoesOptionId)
            ->get();
    }
}
