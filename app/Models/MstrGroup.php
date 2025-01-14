<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrGroup extends Model
{
    use HasFactory;

    protected $table = 'mstr_groups';

    protected $fillable = [
        'Gr_name',
        'Gr_segment',
    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_groupId', 'id');
    }



}