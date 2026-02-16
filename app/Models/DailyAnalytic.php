<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAnalytic extends Model
{
    protected $fillable = [
        'date',
        'total_visitors',
        'unique_visitors',
        'total_page_views',
        'new_users',
        'total_bookings',
        'total_booking_value',
        'completed_bookings',
        'pending_bookings',
        'avg_session_duration',
        'bounce_rate',
        'top_pages',
        'traffic_sources',
    ];

    protected $casts = [
        'date' => 'date',
        'top_pages' => 'array',
        'traffic_sources' => 'array',
    ];
}
