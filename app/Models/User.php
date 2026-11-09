<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'phone', 'phone_verified_at', 'avatar', 'city', 'district', 'address'])]
#[Hidden(['remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'phone_verified_at' => 'datetime',
        ];
    }

    /**
     * طلبات العميل.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    /**
     * أفتارات جاهزة يختار منها العميل بدل رفع صورة.
     *
     * @var list<string>
     */
    public const PRESET_AVATARS = ['🐈‍⬛', '🌙', '⭐', '🌸', '📚', '☕', '🎀', '🦋'];

    /**
     * أول حرف من الاسم، أو أيقونة إذا ما فيه اسم.
     */
    public function initial(): string
    {
        return filled($this->name) ? mb_substr($this->name, 0, 1) : '🤍';
    }

    public function hasImageAvatar(): bool
    {
        return is_string($this->avatar) && (str_starts_with($this->avatar, 'data:image/') || str_starts_with($this->avatar, 'https://'));
    }

    /**
     * المدير: علامة is_admin أو رقمه موجود في ADMIN_PHONES.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || in_array($this->phone, config('services.admin_phones', []), true);
    }
}
