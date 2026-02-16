<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'page_url',
        'page_title',
        'referrer',
        'user_agent',
        'ip_address',
        'country',
        'city',
        'user_id',
        'duration_seconds',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
