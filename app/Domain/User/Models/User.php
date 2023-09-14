<?php

namespace App\Domain\User\Models;

use App\Support\Database\Concerns\HasAlphaNumID;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasAlphaNumID, HasFactory, HasApiTokens;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];
}
