<?php

namespace App\Models\KZ;

use DB;

class KZPrice
{
    CONST PRINT_IO_CATEGORY_ID = 2;
    CONST GALLOREE_CATEGORY_ID = 3;

    public static function getProductModelPrice($model, $side)
    {
        $price = 0;
        $gender = false;

        if (!$model->template) {
            return 0;
        }

        $template = $model->template;
        $variantName = preg_replace('/(\s(Guy|Girl|Infant))$/', '', $template->name);
        $category = $template->category;
        $options  = $model->catalogOptions;


        if (strstr($template->name, 'Guy')) {
            $gender = 'M';
        } elseif (strstr($template->name, 'Girl')) {
            $gender = 'G';
        } elseif (strstr($template->name, 'Infant')) {
            $gender = 'I';
        }

        $map = [
            'reg tee'    => 'aws_price_modifier',
            'reg tees'    => 'aws_price_modifier',
            'wild tee'   => 'aws_price_modifier',
            'wild tees'   => 'aws_price_modifier',
            'phone cases' => 'aws_price_modifier',
            'art prints'  => 'aws_price_modifier',
            'stickers'    => 'aws_price_modifier',
            'socks'       => 'aws_price_modifier',
            'headwear'    => 'aws_price_modifier',
            'shoes'       => 'aws_price_modifier',
            self::PRINT_IO_CATEGORY_ID => 'cscart_aws_printio_product_type_options',
            self::GALLOREE_CATEGORY_ID => 'cscart_aws_galloree_price_modifiers'
        ];

        $printSideConditionMap = [
            'reg tee' => [
                'F' => ['operator' => 'where', 'field' => 'side', 'value' => $side],
                'B' => ['operator' => 'where', 'field' => 'side', 'value' => $side],
                'A' => ['operator' => 'whereIn', 'field' => 'side', 'value' => ['F', 'B'], 'selectExpression' => 'SUM(brand_print_price)']
            ],
            'wild tee' => [
                'F' => ['operator' => 'where', 'field' => '2_sided_print', 'value' => 'N'],
                'B' => ['operator' => 'where', 'field' => '2_sided_print', 'value' => 'N'],
                'A' => ['operator' => 'where', 'field' => '2_sided_print', 'value' => 'Y']
            ]
        ];
        $printSideConditionMap['reg tees'] = $printSideConditionMap['reg tee'];
        $printSideConditionMap['wild tees'] = $printSideConditionMap['wild tee'];

        $category_name_key = strtolower($category->name);
        
        if (array_key_exists($category_name_key, $map)) {
            $tableName = $map[$category_name_key];

            $selectExpression = 'MAX(brand_print_price)';
           
            if (!empty($printSideConditionMap[$category_name_key][$side]['selectExpression'])) {
                $selectExpression = $printSideConditionMap[$category_name_key][$side]['selectExpression'];
            }

            $query = DB::connection('mysql')
                ->table($tableName . ' as prices')
                ->select(DB::raw($selectExpression));

            switch ($category_name_key) {
                case 'reg tee':
                case 'reg tees':
                    // no break, because this is additional condition for reg tees
                    if ($model->getColorOption()) {
                        $isWhiteColor = (
                            $model->getColorOption()->value == '#ffffff'
                            || strtolower($model->getColorOption()->name) == 'white' ||  $model->getColorOption()->value == '#fff'
                        )
                            ? 'Y'
                            : 'N';

                            $query->where('is_white_color', $isWhiteColor)->where('template_id',$model->template->id);
                           
                    }
                    
                case 'wild tee':
                case 'wild tees':
                case 'headwear':
                case 'shoes':
                    $sizeId = $model->getSizeOption()
                        ? $model->getSizeOption()->kz_option_id
                        : null;

                    $query->where('size_id',$sizeId);
                    
                    if (!empty($printSideConditionMap[$category_name_key])) {
                        $operator = $printSideConditionMap[$category_name_key][$side]['operator'];
                        $value    = $printSideConditionMap[$category_name_key][$side]['value'];
                        
                        if ($operator == 'whereIn' && is_array($value)) {    
                            $query->$operator($printSideConditionMap[$category_name_key][$side]['field'], $value);
                        } else {
                            $query->$operator($printSideConditionMap[$category_name_key][$side]['field'], '=', $value);
                        }
                    }
                    
                    break;
                case 'phone cases':
                    $printType = $options[0]->value;
                    $query->where('print_type', $printType);
                    break;
                case 'art prints':
                case 'socks':
                    $sizeId = $model->getSizeOption()
                        ? $model->getSizeOption()->kz_option_id
                        : null;
                    $query->where('size_id', $sizeId);
                    break;
            }
          
            $price = $query->value('price');

        } else {

            $kzProductType = KZProductType::getProductTypeByCategory($category_name_key);
            $kzCategoryId = $kzProductType->category_id;

            if (array_key_exists($kzCategoryId, $map)) {
                $tableName = $map[$kzCategoryId];

                $query = DB::connection('mysql')
                    ->table($tableName . ' as prices')
                    ->select(DB::raw('max(brand_print_price)'));

                switch ($kzCategoryId) {
                    case self::PRINT_IO_CATEGORY_ID:
                        $sku = $options[0]->value;
                        $query->where('sku', $sku);
                        break;
                    case self::GALLOREE_CATEGORY_ID:
                        $sizeId = $model->getSizeOption()
                            ? $model->getSizeOption()->kz_option_id
                            : null;
                        $query->where('description', $category_name_key)
                            ->where('size_id', $sizeId)
                            ->where('limited_edition', 'N');
                        break;
                }
               
                $price = $query->value('price');
            }

        }
        
        $price = $price ?: 0;
        return $price;
    }
}
