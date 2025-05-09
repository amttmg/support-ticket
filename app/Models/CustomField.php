<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $fillable = ['support_topic_id', 'label', 'field_type', 'is_required', 'options', 'sort_order'];

    public function supportTopic()
    {
        return $this->belongsTo(SupportTopic::class);
    }
}
