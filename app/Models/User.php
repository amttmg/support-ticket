<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasSuperAdmin;

    protected $appends = ['branch'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'branch_id',
        'mobile_number',
        'email',
        'password',
        'user_type',
        'ip_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function supportUnits()
    {
        return $this->belongsToMany(SupportUnit::class)->withTimestamps()->withTrashed();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->ip_address = request()->ip();

            if (config('app.auto_detect_ip')) {
                $ipAddress = request()->ip();
                $networkPart = implode('.', array_slice(explode('.', $ipAddress), 0, 3));
                $branch = Branch::where('ip_range', 'like', $networkPart . '.%')->first();
                $user->branch_id = $branch->id;
            }
        });
    }

    public function getBranchAttribute()
    {
        if (config('app.auto_detect_ip')) {
            $ipAddress = $this->ip_address;
            $networkPart = implode('.', array_slice(explode('.', $ipAddress), 0, 3));
            return Branch::where('ip_range', 'like', $networkPart . '.%')->first();
        } else {
            return ($this->branch()->first());
        }
    }
}
