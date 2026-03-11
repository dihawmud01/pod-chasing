<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'prospect_date',
        'vessel_name',
        'port',
        'eta',
        'etb',
        'etd',
        'destination_country',
        'forwarder',
        'delivery_date',
        'status',
        'customs_note',
        'notes',
    ];

    protected $casts = [
        'prospect_date' => 'date',
        'eta'           => 'datetime',
        'etb'           => 'datetime',
        'etd'           => 'datetime',
        'delivery_date' => 'date',
    ];

    public static array $statuses = [
        'planning'          => 'Planning',
        'arranged'          => 'Arranged',
        'waiting_customers' => 'Waiting Customers',
        'customs'           => 'Customs',
        'delayed'           => 'Delayed',
        'cancelled'         => 'Cancelled',
        'completed'         => 'Completed',
    ];

    public function statusLabel(): string
    {
        if ($this->status === 'customs' && $this->customs_note) {
            return 'Customs: ' . $this->customs_note;
        }
        return self::$statuses[$this->status] ?? ucfirst($this->status);
    }

    /** Returns true if delivery_date has passed and status is not done */
    public function isDeliveryOverdue(): bool
    {
        if (! $this->delivery_date) return false;
        if (in_array($this->status, ['completed', 'cancelled'])) return false;
        return $this->delivery_date->isPast();
    }
}
