<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MstrLine extends Model
{
    use HasFactory;

    protected $table = 'mstr_lines';
    public $incrementing = false;
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    //////////////////////


    protected $fillable = [
        '_id',
        'Ln_name'
    ];


    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_lineId', '_id');
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