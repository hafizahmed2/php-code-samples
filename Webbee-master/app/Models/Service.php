<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'time_requried',
        'cleaning_break'
    ];

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function breaktimes()
    {
        return $this->hasMany(Breaktime::class);
    }

    public function getOpeningHours($dayOfWeek)
    {
        return $this->openingHours->firstWhere('day_of_week', $dayOfWeek);
    }


}