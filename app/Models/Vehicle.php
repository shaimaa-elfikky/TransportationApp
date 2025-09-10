<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use BelongsToCompany ,HasFactory;

    protected $fillable = [
        'company_id',
        'make',
        'model',
        'license_plate',
        'year',
        'type',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function isAvailable(string $startTime, string $endTime, ?int $exceptTripId = null): bool
    {
        return $this->trips()
            ->where(function ($query) use ($exceptTripId) {
                if ($exceptTripId) {
                    $query->where('id', '!=', $exceptTripId);
                }
            })
            ->overlapping($startTime, $endTime)
            ->doesntExist();
    }
}
