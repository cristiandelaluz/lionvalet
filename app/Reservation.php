<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;

class Reservation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departure_place', 'departure_meeting_point', 'departure_date', 'departure_hour', 'departure_ticket_number', 'arrival_place',
        'arrival_meeting_point', 'arrival_date', 'arrival_hour', 'arrival_ticket_number', 'status', 'is_forwarding', 'payment_method_id'
    ];

    public function extraServices()
    {
        return $this->belongsToMany(ExtraService::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
