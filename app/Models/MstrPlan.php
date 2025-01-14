<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrPlan extends Model
{
    use HasFactory;
    protected $primaryKey = 'Pl_code';
    public $incrementing = false;

    protected $fillable = [
        'Pl_code',
        'Pl_name',
    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_plId', 'Pl_code');
    }


}