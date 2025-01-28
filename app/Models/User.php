<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'npk';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'npk',
        'name',
        'email',
        'password',
        'idRole',
        'noHp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idRole');
    }

    public function leaderLineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'NpkLeader', 'npk');
    }

    public function sectionLineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'NpkSection', 'npk');
    }

    public function pjStockLineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'NpkPjStock', 'npk');
    }

    public function sect()
    {
        return $this->hasMany(MstrAppr::class, 'NpkSect', 'npk');
    }
    public function dept()
    {
        return $this->hasMany(MstrAppr::class, 'NpkDept', 'npk');
    }
    public function pj()
    {
        return $this->hasMany(MstrAppr::class, 'NpkPj', 'npk');
    }
}