<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = ['ticket_reply_id', 'file_path', 'uploaded_at'];

    public function ticketReply()
    {
        return $this->belongsTo(TicketReply::class);
    }
}
