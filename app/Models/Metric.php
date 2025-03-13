<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metric extends Model
{
    /** @use HasFactory<\Database\Factories\MetricFactory> */
    use HasFactory;

    public $fillable = [
        'ip_user',
        'user_agent',
        'country',
        'city',
        'device',
        'referrer',
    ];
}
