<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title', 'description','bloger_id',
    ];

    public function bloger()
    {
        return $this->belongsTo('App\Bloger');
    }
}
