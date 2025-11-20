<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportUnit extends Model
{
    use SoftDeletes;

    protected $fillable = ['department_id', 'name', 'slack_url'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supportTopics()
    {
        return $this->hasMany(SupportTopic::class);
    }

    public function agents()
    {
        return $this->belongsToMany(User::class); //->withTimestamps()->withTrashed();
    }
}
