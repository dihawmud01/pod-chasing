<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'vessel_name',
        'driver',
        'delivery_address',
        'information',
        'customs_doc',
        'print_status',
        'pod_status',
        'delivered',
        'pod_file',
        'report_date',
    ];

    protected $casts = [
        'customs_doc'  => 'boolean',
        'print_status' => 'boolean',
        'pod_status'   => 'boolean',
        'delivered'    => 'boolean',
        'report_date'  => 'date',
    ];

    /** 5 preset status options */
    public static array $statusTemplates = [
        'Followed up, waiting next information',
        'Delivered, Waiting for POD',
        'Delivered, POD Received',
        "Waiting POD on custom's mail",
    ];

    /** Returns true if value is one of the presets */
    public function isPresetStatus(): bool
    {
        return in_array($this->information, self::$statusTemplates, true);
    }
}
