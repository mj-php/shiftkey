<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['year','make','model','user_id'];

    protected $appends = ['trip_count','trip_miles'];

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function trips()
    {
        return $this->hasMany('App\Trip');
    }

    public function getTripCountAttribute()
    {
        return $this->calculateTripCount();
    }

    private function calculateTripCount()
    {
        $carId = $this->id;
        return Trip::whereHas('car',function($query) use ($carId) {
            $query->where('id',$carId);
        })
            ->count();
    }

    public function getTripMilesAttribute()
    {
        return $this->calculateTripMiles();
    }

    private function calculateTripMiles()
    {
        $carId = $this->id;
        return Trip::whereHas('car',function($query) use ($carId) {
            $query->where('id',$carId);
        })
            ->sum('trips.miles');
    }

}
