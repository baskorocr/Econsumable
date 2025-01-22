<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MstrMaterial extends Model
{
    use HasFactory;

    protected $table = 'mstr_materials';
    protected $primaryKey = '_id'; // Explicitly define the primary key
    public $incrementing = false;  // Disable auto-incrementing
    public $timestamps = false;   // Disable timestamps
    protected $keyType = 'string'; // Set key type as string


    protected $fillable = [
        '_id',
        'Mt_number',
        'Mt_lgId',
        'Mt_desc'
    ];

    public function masterLineGroup()
    {
        return $this->belongsTo(MstrLineGroup::class, 'Mt_lgId', '_id');
    }

    public function consumables()
    {
        return $this->hasMany(MstrConsumable::class, 'Cb_mtId', '_id');
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