<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'password',
        'click_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($link) {
            if (empty($link->short_code)) {
                $link->short_code = $link->generateUniqueShortCode();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accesses(): HasMany
    {
        return $this->hasMany(LinkAccess::class);
    }

    public function generateUniqueShortCode(): string
    {
        do {
            $shortCode = Str::random(6);
        } while (self::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isAccessible(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function getShortUrlAttribute(): string
    {
        return url('/' . $this->short_code);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}