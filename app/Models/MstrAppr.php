<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrAppr extends Model
{
    use HasFactory;

    protected $table = 'mstr_apprs';
    protected $primaryKey = '_id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_order',
        'ConsumableId',
        'NpkSect',
        'NpkDept',
        'NpkPj',
        'ApprSectDate',
        'ApprDeptDate',
        'ApprPjStokDate',
        'jumlah',
        'token',
        'status',
        'lineFrom'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                // Generate custom 24-character ID
                $model->{$model->getKeyName()} = self::generateCustomID();
            }
        });
    }

    protected static function generateCustomID()
    {
        // Generate a random 24-character alphanumeric string
        return substr(md5(uniqid(mt_rand(), true)), 0, 24);
    }

    // Relasi ke OrderSegment
    public function orderSegment()
    {
        return $this->hasOne(OrderSegment::class, '_id', 'no_order');
    }

    // Relasi ke MstrConsumable
    public function consumable()
    {
        return $this->hasOne(MstrConsumable::class, '_id', 'ConsumableId');
    }

    // Relasi ke User untuk NpkSect (Section)
    public function sect()
    {
        return $this->belongsTo(User::class, 'NpkSect', 'npk');
    }

    // Relasi ke User untuk NpkDept (Department)
    public function dept()
    {
        return $this->belongsTo(User::class, 'NpkDept', 'npk');
    }

    // Relasi ke User untuk NpkPj (Project)
    public function pj()
    {
        return $this->belongsTo(User::class, 'NpkPj', 'npk');
    }

    // Relasi ke SapFail
    public function sapFails()
    {
        return $this->hasMany(SapFail::class, 'idAppr', '_id');
    }

    // Relasi ke User untuk Pj (Project Manager), jika diperlukan
    public function user()
    {
        return $this->belongsTo(User::class, 'NpkPj', 'npk');
    }
}