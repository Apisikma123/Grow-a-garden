<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $table = 'event_type_catalog';

    protected $fillable = [
        'code',
        'label',
        'category',
        'default_priority',
    ];
}
