<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseActivation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'license_id',
        'domain',
        'ip',
        'user_agent',
        'success',
        'failure_reason',
        'validated_at',
    ];

    protected $casts = [
        'success' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
