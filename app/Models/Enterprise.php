<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enterprise extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'enterprises_status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branchs(): HasMany
    {
        return $this->hasMany(Branch::class, 'enterprise_id');
    }
}