<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MstrPlan;

class MstrLineGroup extends Model
{
    use HasFactory;

    protected $table = 'mstr_line_groups';
    protected $primaryKey = 'Lg_code';

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
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
        return $this->belongsTo(MstrPlan::class, 'Lg_plId', 'Pl_code');
    }

    public function costCenter()
    {
        return $this->belongsTo(MstrCostCenter::class, 'Lg_csId', 'Cs_code');
    }

    public function line()
    {
        return $this->belongsTo(MstrLine::class, 'Lg_lineId', 'id');
    }

    public function group()
    {
        return $this->belongsTo(MstrGroup::class, 'Lg_groupId', 'id');
    }

    public function sloc()
    {
        return $this->belongsTo(MstrSloc::class, 'Lg_slocId', 'Tp_mtCode');
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

    public function materials()
    {
        return $this->hasMany(MstrMaterial::class, 'Mt_lgId', 'Lg_code');
    }
}