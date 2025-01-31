<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MstrPlan;
use Illuminate\Support\Str;

class MstrLineGroup extends Model
{
    use HasFactory;



    protected $table = 'mstr_line_groups';
    protected $primaryKey = '_id';

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        '_id',
        'Lg_code',
        'Lg_plId',
        'Lg_csId',
        'Lg_lineId',
        'Lg_groupId',
        'Lg_slocId',
        'NpkLeader',
        'NpkSection',
        'NpkPjStock',
    ];


    public function plan()
    {
        return $this->belongsTo(MstrPlan::class, 'Lg_plId', '_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(MstrCostCenter::class, 'Lg_csId', '_id');
    }


    public function lines() // Gunakan plural untuk menunjukkan banyak data
    {
        return $this->hasMany(MstrLine::class, 'Ln_lgId', '_id');
    }

    public function group()
    {
        return $this->belongsTo(MstrGroup::class, 'Lg_groupId', '_id');
    }

    public function sloc()
    {
        return $this->belongsTo(MstrSloc::class, 'Lg_slocId', '_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'NpkLeader', 'npk');
    }

    public function section()
    {
        return $this->belongsTo(User::class, 'NpkSection', 'npk');
    }

    public function pjStock()
    {
        return $this->belongsTo(User::class, 'NpkPjStock', 'npk');
    }

    public function consumable()
    {
        return $this->hasMany(MstrConsumable::class, 'Cb_lgId', '_id');
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