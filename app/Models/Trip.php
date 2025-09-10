<?php

namespace App\Models;

use App\Enums\TripStatus;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use BelongsToCompany , HasFactory;

    protected $fillable = [
        'company_id',
        'driver_id',
        'vehicle_id',
        'name',
        'description',
        'origin',
        'destination',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'status' => TripStatus::class,
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            TripStatus::Scheduled->value,
            TripStatus::Started->value,
        ]);
    }

    public function scopeOverlapping($query, string $startTime, string $endTime)
    {
        return $query
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);
    }
}
