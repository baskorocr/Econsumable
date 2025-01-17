<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MstrConsumable extends Model
{
    use HasFactory;

    protected $table = 'mstr_consumables';
    protected $primaryKey = '_id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'Cb_number',
        'Cb_mtId',
        'Cb_desc',

    ];

    public function material()
    {
        return $this->belongsTo(MstrMaterial::class, 'Cb_mtId', '_id');
    }



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

}