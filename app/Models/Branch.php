<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'code',
        'name',
        'ip_range',
        'active',
    ];

    public function getFormattedNameAttribute()
    {
        return "{$this->name} ({$this->code})";
    }
}
