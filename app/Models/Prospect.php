<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'vessel_name',
        'port',
        'eta',
        'etb',
        'etd',
        'destination_country',
        'forwarder',
        'delivery_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'eta'           => 'date',
        'etb'           => 'date',
        'etd'           => 'date',
        'delivery_date' => 'date',
    ];

    public static array $statuses = [
        'planning'          => 'Planning',
        'confirmed'         => 'Confirmed',
        'waiting_customers' => 'Waiting Customers',
        'customs'           => 'Customs',
        'delayed'           => 'Delayed',
        'cancelled'         => 'Cancelled',
        'completed'         => 'Completed',
    ];

    public function statusLabel(): string
    {
        return self::$statuses[$this->status] ?? ucfirst($this->status);
    }
}
