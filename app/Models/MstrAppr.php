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
        'NpkUser',
        'NpkSect',
        'NpkDept',
        'NpkPj',
        'ApprSectDate',
        'ApprDeptDate',
        'ApprPjStokDate',
        'jumlah',
        'token',
        'status',


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

    public function orderSegment()
    {
        return $this->hasOne(OrderSegment::class, '_id', 'no_order');
    }

    public function consumable()
    {
        return $this->hasOne(MstrConsumable::class, '_id', 'ConsumableId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'NpkUser', 'npk');
    }

}