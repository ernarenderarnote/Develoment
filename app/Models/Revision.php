<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    
    // revision_type
    // revisionable_id
    // user_id
    // key
    
    protected $table = 'revisions';
        
    /***********
     * Relations
     */

        public function user()
        {
            return $this->belongsTo(\App\Models\User::class, 'user_id');
        }
}
