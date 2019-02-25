<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Components\Money;

use App\Transformers\ProductVariant\ProductVariantFullTransformer;
use App\Transformers\Serializers\SimpleArraySerializer;

class ProductVariant extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \Culpa\Traits\Blameable;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use Traits\DatetimeTrait;
    use Traits\VisibilityTrait;
    use Traits\ModerationStatusTrait;
    use \App\Presenters\ProductVariantPresenter;

    // id
    // user_id
    // product_id
    // status
    // moderation_status
    // moderation_status_comment
    // provider_variant_id
    // name
    // print_side
    // product_model_id
    // created_at
    // updated_at

    const STATUS_ACTIVE = 'active';
    const STATUS_IGNORED = 'ignored';

    const MODERATION_STATUS_NOT_APPROVED = 'not_approved'; // waiting for the client
    const MODERATION_STATUS_ON_MODERATION = 'on_moderation'; // waiting for the admin
    const MODERATION_STATUS_APPROVED = 'approved';
    const MODERATION_STATUS_DECLINED = 'declined';

    const PRINT_SIDE_FRONT = 'front';
    const PRINT_SIDE_BACK = 'back';
    const PRINT_SIDE_ALL = 'all';

    protected $table = 'product_variants';

    protected $fillable = [
        'user_id',
        'product_id',
        'provider_variant_id'
    ];

    protected $casts = [
        'meta' => 'object'
    ];

    // revisions
    protected $revisionEnabled = true;
    protected $revisionCreationsEnabled = true;
    protected $keepRevisionOf = [
        'status',
        'moderation_status',
        'moderation_status_comment'
    ];

    // blameable
    protected $blameable = [
        'created' => 'user_id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array_merge($this->attributes, [
          'status' => static::STATUS_ACTIVE,
          'moderation_status' => static::MODERATION_STATUS_NOT_APPROVED,
        ]), true);
        parent::__construct($attributes);
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /************
     * Accessors
     */

    /************
     * Mutators
     */

        public function setStatusAttribute($value)
        {
            if (!$this->status && !$value) {
                $this->attributes['status'] = static::STATUS_ACTIVE;
            }
            else {
                $this->attributes['status'] = $value;
            }
        }

    /*********
     * Scopes
     */



    /***********
     * Relations
     */

        public function user()
        {
            return $this->belongsTo(\App\Models\User::class, 'user_id');
        }

        public function product()
        {
            return $this->belongsTo(\App\Models\Product::class, 'product_id');
        }

        public function productModel()
        {
            return $this->belongsTo(\App\Models\ProductModel::class, 'product_model_id')->withTrashed();
        }

        public function model()
        {
            return $this->productModel();
        }

        // TODO: not needed for now
        //public function files()
        //{
        //    return $this->belongsToMany(\App\Models\File::class, 'product_variant_files')
        //        ->where(File::getTableName().'.type', File::TYPE_PRINT_FILE)
        //        ->withPivot('type');
        //}

        public function mockups()
        {
            return $this->belongsToMany(\App\Models\File::class, 'product_variant_files')
                ->where(File::getTableName().'.type', File::TYPE_PRINT_FILE_MOCKUP)
                ->withPivot('type');
        }

        public function mockupsBack()
        {
            return $this->belongsToMany(\App\Models\File::class, 'product_variant_files')
                ->where(File::getTableName().'.type', File::TYPE_PRINT_FILE_MOCKUP_BACK)
                ->withPivot('type');
        }

        public function orders()
        {
            return $this->belongsToMany(\App\Models\Order::class, 'orders_product_variants_relations');
        }

        public function designerFiles()
        {
            return $this->belongsToMany(\App\Models\ProductDesignerFile::class, 'product_variant_designer_file', 'product_variant_id', 'product_designer_files_id');
        }

        // yes, we're using only first one for now
        // currently assigning design interfcae for this relation
        // uses relations "one design file -> many variants"
        public function getDesignerFile($clientFileDesignLocation)
        {
            return $this->designerFiles()
                ->whereHas('clientFile', function ($query) use($clientFileDesignLocation) {
                    $query->where('design_location', $clientFileDesignLocation);
                })
                ->first();
        }

    /***********
     * Checks
     */

        public function isActive()
        {
            return $this->status == static::STATUS_ACTIVE;
        }

        public function isIgnored()
        {
            return $this->status == static::STATUS_IGNORED;
        }

        public function isSynced()
        {
            return (bool)$this->model;
        }

        public function isTaxable()
        {
            return (bool)($this->user
                ? $this->user->is_taxable
                : false
            );
        }

        public function modelPriceIsSet()
        {
            $price = 0;
            if ($this->print_side == static::PRINT_SIDE_ALL) {
                $price = $this->model->both_sides_price;
            }
            else if ($this->print_side == static::PRINT_SIDE_FRONT) {
                $price = $this->model->price;
            }
            else if ($this->print_side == static::PRINT_SIDE_BACK) {
                $price = $this->model->back_price;
            }

            return $price > 0;
        }

    /**********
     * Counters
     */



    /*************
     * Decorators
     */

        // moved to presenter

    /*********
     * Helpers
     */

        public static function statusName($status)
        {
            $statuses = static::listStatuses();
            return isset($statuses[$status]) ? $statuses[$status] : null;
        }

        public static function listStatuses()
        {
            return [
                static::STATUS_ACTIVE => trans('labels.active'),
                static::STATUS_IGNORED => trans('labels.ignored')
            ];
        }


    /**************
     * Transformers
     */

        public function transformFull()
        {
            $resource = \FractalManager::item($this, new ProductVariantFullTransformer);
            return \FractalManager::i(new SimpleArraySerializer())->createData($resource)->toArray();
        }

    /***********
     * Functions
     */

        public function createVariant()
        {
            $result = $this->save();

            return $result;
        }

        public static function createOrUpdateShopifyVariant(User $user, Product $product, $shopifyVariant)
        {
            $variant = static::firstOrNew([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'provider_variant_id' => $shopifyVariant->id
            ]);

            $variant->name = $shopifyVariant->title;
            $variant->provider_variant_id = $shopifyVariant->id;
            $variant->meta = $shopifyVariant;

            if (!$variant->id) {
                $variant->createVariant();
            }
            else {
                $variant->save();
            }

            return $variant;
        }

        public function changeStatus($status)
        {
            $this->status = $status;
            return $this->save();
        }

        public function activate()
        {
            return $this->changeStatus(static::STATUS_ACTIVE);
        }

        public function ignore()
        {
            return $this->changeStatus(static::STATUS_IGNORED);
        }

        public static function findByProviderId($provider_id)
        {
            return static::where('provider_variant_id', $provider_id)
                ->first();
                /*718864448*/
        }

        /**
         * We will compare generated shopify options for variant to get according mntz variant
         *
         * $options: [{
         *        "name": "Size",
         *        "position": 1,
         *        "values": ["S"]
         *    }, {
         *        "name": "Color",
         *        "position": 2,
         *        "values": ["White"]
         *    }]
         */
        public static function findForProductByShopifyOptions(Product $product, $shopifyVariant)
        {
            $variants = static::where('product_id', $product->id)
                ->get();

            foreach($variants as $variant) {
                $optionsForCheck = [
                    'option1' => null,
                    'option2' => null,
                    'option3' => null
                ];

                $i = 1;
                foreach ($variant->model->catalogOptions as $option) {
                    if ($i > 3) {
                        break;
                    }

                    $optionsForCheck['option'.$i] = $option->name;
                    $i++;
                }

                if (
                    $optionsForCheck['option1'] == $shopifyVariant->option1
                    && $optionsForCheck['option2'] == $shopifyVariant->option2
                    && $optionsForCheck['option3'] == $shopifyVariant->option3
                ) {
                    return $variant;
                }
            }

            return null;
        }

        public function unsync()
        {
            $this->files()->detach();
            $this->product_model_id = null;
            return $this->save();
        }

        public static function getPopularProductsStatsForStore($store_id, $year, $asCategory = 'category')
        {
            $category = CatalogCategory::getTableName();
            $template = ProductModelTemplate::getTableName();
            $model = ProductModel::getTableName();
            $variants = static::getTableName();
            $order = Order::getTableName();

            if ($asCategory == 'product') {
                $categoryColumn = $template.'.name as category';
                $groupBy = $template.'.id';
            }
            else {
                $categoryColumn = $category.'.name as category';
                $groupBy = $category.'.id';
            }

            $results = DB::table(static::getTableName())
                ->select(
                    DB::raw('COUNT('.$variants.'.id) AS products'),
                    $categoryColumn
                )
                ->join($model, $model.'.id', '=', $variants.'.product_model_id')
                ->join($template, $template.'.id', '=', $model.'.template_id')
                ->join($category, $category.'.id', '=', $template.'.category_id')
                ->join('orders_product_variants_relations', 'orders_product_variants_relations.product_variant_id', '=', static::getTableName().'.id')
                ->join($order, $order.'.id', '=', 'orders_product_variants_relations.order_id')
                ->where($order.'.store_id', $store_id)
                ->where(DB::raw('YEAR('.$order.'.created_at)'), $year)
                ->groupBy($groupBy)
                ->get();

            return collect($results);
        }


    /*************
     * Collections
     */


}
