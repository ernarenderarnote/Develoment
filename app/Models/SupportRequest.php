<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\Fractal;

use App\Transformers\Support\TicketTransformer;
use App\Transformers\Support\TicketBriefTransformer;
use App\Transformers\Support\AnswerDisputeTransformer;
use App\Transformers\Support\AnswerDisputeBriefTransformer;
use App\Models\Answer;

class SupportRequest extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\DatetimeTrait;
    use \Culpa\Traits\Blameable;

    // id
    // user_id
    // name
    // email
    // subject
    // text
    // status
    // created_at
    // updated_at
    // meta
    // type

    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_CLOSED = 'closed';

    const TYPE_TICKET = 'ticket';
    const TYPE_REFUND = 'refund';

    protected $table = 'support_requests';

    protected $fillable = [
        'subject',
        'text'
    ];

    protected $casts = [
        'meta' => 'object'
    ];

    // revisions
    protected $revisionEnabled = true;
    protected $revisionCreationsEnabled = true;
    protected $keepRevisionOf = [
        'status'
    ];

    // blameable
    protected $blameable = [
        'created' => 'user_id'
    ];

    protected $order = null;

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array_merge($this->attributes, [
          'status' => static::STATUS_NEW
        ]), true);
        parent::__construct($attributes);
    }

    /************
     * Mutators
     */

        public function setStatusAttribute($value)
        {
            if (!$this->status && !$value) {
                $this->attributes['status'] = static::STATUS_NEW;
            }
            else {
                $this->attributes['status'] = $value;
            }
        }

    /*********
     * Scopes
     */

        public function scopeOwns($query, $user)
        {
            return $query
                ->where('user_id', $user->id);
        }


    /***********
     * Relations
     */

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function order()
        {
            if (
                !empty($this->meta->order_id)
                && !$this->order
            ) {
                $this->order = Order::find($this->meta->order_id);
            }

            return $this->order;
        }

    /***********
     * Checks
     */

        public function isTicket()
        {
            return ($this->type == static::TYPE_TICKET);
        }

        public function isRefund()
        {
            return ($this->type == static::TYPE_REFUND);
        }

        public function isNew()
        {
            return $this->status == static::STATUS_NEW;
        }

    /**************
     * Transformers
     */

        public function transformBrief()
        {
            if ($this->isTicket()) {
                $resource = \FractalManager::item($this, new TicketBriefTransformer);
            }

            return \FractalManager::i()->createData($resource)->toArray();
        }

        public function transformFull()
        {
            if ($this->isTicket()) {
                $resource = \FractalManager::item($this, new TicketTransformer);
            }

            return \FractalManager::i()->createData($resource)->toArray();
        }

    /**********
     * Counters
     */

        public static function countNewTickets()
        {
            return static::getNewTicketsQuery()->count();
        }

        public static function countPendingTickets()
        {
            return static::getPendingTicketsQuery()->count();
        }

        public static function countNewRefunds()
        {
            return static::getNewRefundsQuery()->count();
        }


    /*************
     * Decorators
     */

        public function getStatusName()
        {
            return static::statusName($this->status);
        }

    /*********
     * Helpers
     */

        public static function statusName($status)
        {
            $statuses = static::listStatuses();
            return $statuses[$status];
        }

        public static function listStatuses()
        {
            return [
                static::STATUS_NEW => trans('labels.support_status__new'),
                static::STATUS_PENDING => trans('labels.support_status__pending'),
                static::STATUS_CLOSED => trans('labels.support_status__closed')
            ];
        }

        public static function typeName($type)
        {
            $types = static::listTypes();
            return $types[$type];
        }

        public static function listTypes()
        {
            return [
                static::TYPE_TICKET => trans('labels.support_type__ticket'),
                static::TYPE_REFUND => trans('labels.support_type__refund'),
            ];
        }

    /***********
     * Functions
     */

        public function openTicket($data, $meta = [])
        {
            $this->fill($data);
            $this->type = static::TYPE_TICKET;
            $this->meta = $meta;
            $result = $this->save();

            \Event::fire(new \App\Events\Support\TicketOpenedEvent($this));

            return $result;
        }

        public function openRefund($data, $meta = [])
        {
            $this->fill($data);
            $this->type = static::TYPE_REFUND;
            $this->meta = $meta;
            $result = $this->save();

            \Event::fire(new \App\Events\Support\RefundOpenedEvent($this));

            return $result;
        }

        public function changeStatus($status)
        {
            $this->status = $status;
            return $this->save();
        }

        public function pending()
        {
            return $this->changeStatus(static::STATUS_PENDING);
        }

    /*************
     * Collections
     */

        public static function getAllTicketsQuery()
        {
            return static::where(function($query) {
                    $query->where('type', static::TYPE_TICKET);
                })
                ->with('user')
                ->orderBy('updated_at', 'desc');
        }

        public static function getAllOwnTicketsQuery($user)
        {
            return static::where(function($query) {
                    $query->where('type', static::TYPE_TICKET);
                })
                ->owns($user)
                ->with('user')
                ->orderBy('updated_at', 'desc');
        }

        public static function getNewTicketsQuery()
        {
            return static::where(function($query) {
                    $query->where('status', static::STATUS_NEW);
                    $query->where('type', static::TYPE_TICKET);
                })
                ->with('user');
        }

        public static function getPendingTicketsQuery()
        {
            return static::where(function($query) {
                    $query->where('status', static::STATUS_PENDING);
                    $query->where('type', static::TYPE_TICKET);
                })
                ->with('user');
        }

        // refunds
        public static function getAllRefundsQuery()
        {
            return static::where(function($query) {
                    $query->where('type', static::TYPE_REFUND);
                })
                ->with('user')
                ->orderBy('updated_at', 'desc');
        }

        public static function getNewRefundsQuery()
        {
            return static::where(function($query) {
                    $query->where('status', static::STATUS_NEW);
                    $query->where('type', static::TYPE_REFUND);
                })
                ->with('user');
        }
}
