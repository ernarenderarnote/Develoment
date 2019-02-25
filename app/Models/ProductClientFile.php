<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClientFile extends Model
{

    // id
    // product_id
    // mockup_id
    // print_id

    const LOCATION_FRONT_CHEST = 'front_chest';
    const LOCATION_BACK = 'back';

    public $timestamps = false;
    protected $table = 'product_client_files';

    protected $fillable = [
        'design_location',
        'product_id',
        'mockup_id',
        'print_id',
        'source_id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array_merge($this->attributes, [
          'design_location' => static::LOCATION_FRONT_CHEST
        ]), true);
        parent::__construct($attributes);
    }

    /************
     * Mutators
     */

        public function setDesignLocationAttribute($value)
        {
            if (!$this->status && !$value) {
                $this->attributes['design_location'] = static::LOCATION_FRONT_CHEST;
            }
            else {
                $this->attributes['design_location'] = $value;
            }
        }

    /*********
     * Scopes
     */

    /***********
     * Relations
     */

        public function product()
        {
            return $this->belongsTo(\App\Models\Product::class, 'product_id');
        }

        public function printFile()
        {
            return $this->hasOne(\App\Models\File::class, 'id', 'print_id');
        }

        public function sourceFile()
        {
            return $this->hasOne(\App\Models\FileAttachment::class, 'id', 'source_id');
        }

        public function mockup()
        {
            return $this->hasOne(\App\Models\File::class, 'id', 'mockup_id');
        }

        public function designerFiles()
        {
            return $this->hasMany(\App\Models\ProductDesignerFile::class);
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

        public function getLocationName()
        {
            return static::locationName($this->design_location);
        }

        public function designerFile($type = null)
        {
            $files = $this->designerFiles;

            return $files->filter(function($designerFile) use($type) {
                $fileType = null;
                if ($designerFile->file && $designerFile->file->type) {
                    $fileType = $designerFile->file->type;
                }

                return $fileType == $type;
            })->first();
        }

    /*********
     * Helpers
     */

        public static function locationName($location)
        {
            $locations = static::listLocations();
            return isset($locations[$location]) ? $locations[$location] : null;
        }

        public static function listLocations()
        {
            return [
                static::LOCATION_FRONT_CHEST => trans('labels.design_location__front_chest'),
                static::LOCATION_BACK => trans('labels.design_location__back'),
            ];
        }

    /***********
     * Functions
     */



    /*************
     * Collections
     */


}
