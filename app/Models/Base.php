<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    use \App\Models\Traits\DatetimeTrait;
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
