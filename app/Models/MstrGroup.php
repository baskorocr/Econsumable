<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MstrGroup extends Model
{
    use HasFactory;

    protected $table = 'mstr_groups';
    protected $primaryKey = '_id'; // Explicitly define the primary key
    public $incrementing = false;  // Disable auto-incrementing
    public $timestamps = false;   // Disable timestamps
    protected $keyType = 'string';

    protected $fillable = [
        '_id',
        'Gr_name',
        'Gr_segment',
    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_groupId', '_id');
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