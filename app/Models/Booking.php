<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'user_id',
        'kyc_id',
        'customerName',
        'customer_name',
        'customerEmail',
        'customer_email',
        'customerPhone',
        'customer_phone',
        'startDate',
        'start_date',
        'endDate',
        'end_date',
        'status',
        'pricing',
        'totalPrice',
        'total_price',
        'addOns',
        'add_ons',
        'payment',
        'conditionReports',
        'condition_reports',
        'expiresAt',
        'expires_at',
        'confirmedAt',
        'confirmed_at',
        'checkedOutAt',
        'checked_out_at',
        'returnedAt',
        'returned_at',
        'canceledAt',
        'canceled_at',
        'paymentMethod',
        'payment_method',
        'payment_status',
        'phone_number',
        'mobileMoneyNumber',
        'mobile_money_number',
        'idType',
        'id_type',
        'idNumber',
        'id_number',
        'idDocument',
        'id_document',
    ];

    protected $casts = [
        'pricing' => 'array',
        'addOns' => 'array',
        'payment' => 'array',
        'conditionReports' => 'array',
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'expiresAt' => 'datetime',
        'confirmedAt' => 'datetime',
        'checkedOutAt' => 'datetime',
        'returnedAt' => 'datetime',
        'canceledAt' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kyc()
    {
        return $this->belongsTo(KycVerification::class, 'kyc_id');
    }

    public function conditionReports()
    {
        return $this->hasMany(ConditionReport::class, 'booking_id');
    }
}
