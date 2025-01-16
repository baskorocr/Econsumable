<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MstrSloc extends Model
{
    use HasFactory;
    protected $table = 'mstr_slocs';
    protected $primaryKey = '_id'; // Explicitly define the primary key
    public $incrementing = false;  // Disable auto-incrementing
    public $timestamps = false;   // Disable timestamps
    protected $keyType = 'string'; // Set key type as string


    protected $fillable = [
        '_id',
        'Tp_mtCode',
        'Tp_name',
    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_slocId', '_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}