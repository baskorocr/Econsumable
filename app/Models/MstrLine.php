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
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'Ln_name'
    ];


    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_lineId', 'id');
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