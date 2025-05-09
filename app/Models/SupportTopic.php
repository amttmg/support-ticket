<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTopic extends Model
{
    use SoftDeletes;

    protected $fillable = ['support_unit_id', 'name'];

    public function supportUnit()
    {
        return $this->belongsTo(SupportUnit::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
