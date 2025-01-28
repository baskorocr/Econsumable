<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SapFail extends Model
{
    use HasFactory;
    protected $table = 'sap_fails';
    protected $primaryKey = '_id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idCb',
        'idAppr',
        'Code',
        'Desc_fails'

    ];


    public function consumable()
    {
        return $this->belongsTo(MstrConsumable::class, 'idCb', '_id');
    }

    public function appr()
    {
        return $this->belongsTo(MstrAppr::class, 'idAppr', '_id');
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