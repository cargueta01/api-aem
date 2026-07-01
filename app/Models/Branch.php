<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $table = 'branchs';

    protected $fillable = [
        'enterprise_id',
        'name',
        'doc_number',
        'municipality_codigo',
        'branchs_status',
    ];

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }
}