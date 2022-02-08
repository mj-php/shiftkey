<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Trip extends Model
{
    protected $fillable = ['date','miles','car_id'];

    protected $appends = ['total'];

    protected $dates = ['date'];

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function getTotalAttribute()
    {
        return $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $loggedUserId = 1;//Auth::user()->getAuthIdentifier();
        $tripId = $this->id;

        return Trip::where('id','<=',$tripId)->whereHas('car',function($query) use ($loggedUserId) {
            $query->where('user_id',$loggedUserId);
        })
            ->sum('trips.miles');
    }

    /**
     * Formats trips date.
     *
     * @param  string  $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->format("m/d/Y");
    }
}
