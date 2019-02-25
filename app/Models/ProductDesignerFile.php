<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * File attached by designer to client's product
 * contains file type, AR3 file, positions and product model template variants
 */
class ProductDesignerFile extends Model
{

    // id
    // user_id
    // name
    // status
    // type
    // created_at
    // updated_at

    const PRINT_SIZE_SMALL = 'small';
    const PRINT_SIZE_MEDIUM = 'medium';
    const PRINT_SIZE_LARGE = 'large';

    const PRINT_POSITION_FRONT_UPPER = 'front_upper';
    const PRINT_POSITION_FRONT_MIDDLE = 'front_middle';
    const PRINT_POSITION_FRONT_LOWER = 'front_lower';
    const PRINT_POSITION_BACK_UPPER = 'back_upper';
    const PRINT_POSITION_BACK_MIDDLE = 'back_middle';
    const PRINT_POSITION_BACK_LOWER = 'back_lower';

    public $timestamps = false;
    protected $table = 'product_designer_files';
    protected $fillable = [
        'product_client_file_id',
        'file_id'
    ];

    /************
     * Mutators
     */



    /*********
     * Scopes
     */

    /***********
     * Relations
     */

        public function file()
        {
            return $this->hasOne(\App\Models\FileAttachment::class, 'id', 'file_id');
        }

        public function clientFile()
        {
            return $this->belongsTo(\App\Models\ProductClientFile::class, 'product_client_file_id');
        }

        public function variants()
        {
            return $this->belongsToMany(\App\Models\ProductVariant::class, 'product_variant_designer_file', 'product_designer_files_id', 'product_variant_id');
        }

    /***********
     * Checks
     */

    /**********
     * Counters
     */



    /*************
     * Decorators
     */

        public function getPrintPositionName()
        {
            return static::listPrintPositions($this->print_position);
        }

        public function getPrintSize()
        {
            $size = null;
            if ($this->file && $this->file->type) {
                if (stristr($this->file->type, 'small')) {
                    $size = static::PRINT_SIZE_SMALL;
                }
                else if (stristr($this->file->type, 'medium')) {
                    $size = static::PRINT_SIZE_MEDIUM;
                }
                else if (stristr($this->file->type, 'large')) {
                    $size = static::PRINT_SIZE_LARGE;
                }
            }

            return $size;
        }

        public function getPrintPosition()
        {
            return $this->print_position;
        }

    /*********
     * Helpers
     */

        public static function listTypes()
        {
            $types = File::listTypes();

            return $types->filter(function($value, $key) {
                return stristr($key, 'product_ar3');
            });
        }

        public static function listPrintPositions()
        {
            return [
                static::PRINT_POSITION_FRONT_UPPER => trans('labels.print_position_front_upper'),
                static::PRINT_POSITION_FRONT_MIDDLE => trans('labels.print_position_front_middle'),
                static::PRINT_POSITION_FRONT_LOWER => trans('labels.print_position_front_lower'),
                static::PRINT_POSITION_BACK_UPPER => trans('labels.print_position_back_upper'),
                static::PRINT_POSITION_BACK_MIDDLE => trans('labels.print_position_back_middle'),
                static::PRINT_POSITION_BACK_LOWER => trans('labels.print_position_back_lower')
            ];
        }

    /***********
     * Functions
     */



    /*************
     * Collections
     */


}
