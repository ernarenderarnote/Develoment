<?php

namespace App\Models;

use Laravel\Spark\Token;

class ApiToken extends Token
{
    const TYPE_DEFAULT = '';
    const TYPE_STORE = 'store';
    
    /***********
     * Relations
     */
        
        public function store()
        {
            return $this->belongsTo(Store::class, 'store_id');
        }
}
