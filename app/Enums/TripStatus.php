<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TripStatus: int implements HasColor, HasLabel
{
    case Scheduled = 0;
    case Started = 1;
    case Completed = 2;
    case Cancelled = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Scheduled => 'Scheduled',
            self::Started => 'Started',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Scheduled => 'primary',
            self::Started => 'warning',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
