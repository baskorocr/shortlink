<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkAccess extends Model
{
    use HasFactory;

    protected $table = 'link_accesses';

    protected $fillable = [
        'link_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'city',
        'accessed_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}