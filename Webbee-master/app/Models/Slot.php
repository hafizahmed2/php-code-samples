<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'max_clients',
        'service_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
