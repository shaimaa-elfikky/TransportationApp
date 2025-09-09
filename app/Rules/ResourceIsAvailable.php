<?php

namespace App\Rules;

use Closure;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ResourceIsAvailable implements ValidationRule, DataAwareRule
{
   
    protected array $data = [];

    protected ?int $tripIdToIgnore = null;


    public function __construct(protected string $resourceType)
    {
    }

  
    public function setData(array $data): static
    {
        $this->data = $data;

        if (request()->route('record')) {
            $this->tripIdToIgnore = request()->route('record')->id;
        }

        return $this;
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startTime = $this->data['start_time'] ?? null;
        $endTime = $this->data['end_time'] ?? null;
        $resourceId = $value;

        if (!$startTime || !$endTime || !$resourceId) {
            return;
        }

        $model = match ($this->resourceType) {
            'driver' => Driver::find($resourceId),
            'vehicle' => Vehicle::find($resourceId),
            default => null,
        };

        if (!$model) {
            return; 
        }

        if (!$model->isAvailable($startTime, $endTime, $this->tripIdToIgnore)) {
            $fail("The selected {$this->resourceType} is not available for the chosen time frame.");
        }
    }
}