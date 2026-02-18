<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $table = 'licenses';

    protected $fillable = [
        'key',
        'domain',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function activations()
    {
        return $this->hasMany(LicenseActivation::class);
    }
}
