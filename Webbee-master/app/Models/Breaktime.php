<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breaktime extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'service_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
