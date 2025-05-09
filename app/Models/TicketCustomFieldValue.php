<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCustomFieldValue extends Model
{
    use SoftDeletes;

    protected $fillable = ['ticket_id', 'custom_field_id', 'value'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
