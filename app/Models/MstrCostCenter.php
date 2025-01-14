<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrCostCenter extends Model
{
    use HasFactory;

    protected $table = 'mstr_cost_centers';
    protected $primaryKey = 'Cs_code';
    public $incrementing = false;
    public $timestamps = false;




    protected $fillable = [
        'Cs_code',
        'Cs_name',

    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_csId', 'Cs_code');
    }




}