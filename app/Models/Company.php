<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companys';

    protected $fillable = [
        'name',
        'companys_status',
    ];

    public function enterprises(): HasMany
    {
        return $this->hasMany(Enterprise::class, 'company_id');
    }
}