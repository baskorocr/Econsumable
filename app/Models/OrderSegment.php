<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderSegment extends Model
{


    use HasFactory;

    protected $table = 'order_segments';
    protected $primaryKey = '_id'; // Explicitly define the primary key
    public $incrementing = false;  // Disable auto-incrementing
    // Disable timestamps
    protected $keyType = 'string';

    // Set key type as string


    protected $fillable = [
        '_id',
        'noOrder',
        'NpkUser'

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
    public function user()
    {
        return $this->belongsTo(User::class, 'NpkUser', 'npk');
    }

    protected static function generateCustomID()
    {
        // Generate a random 24-character alphanumeric string
        return substr(md5(uniqid(mt_rand(), true)), 0, 24);
    }
    public function mstrApprs()
    {
        return $this->hasMany(MstrAppr::class, 'no_order', '_id');
    }
}