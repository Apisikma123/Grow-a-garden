<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantCategory extends Model
{
    protected $guarded = [];

    public function templates()
    {
        return $this->hasMany(PlantTemplate::class, 'category_id');
    }
}
