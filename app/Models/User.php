<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory,HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed', 'role' => UserRole::class];

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'app') {
            return true;
        }

        return false;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
