<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrSloc extends Model
{
    use HasFactory;
    protected $table = 'mstr_slocs';
    protected $primaryKey = 'Tp_mtCode';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'Tp_mtCode',
        'Tp_name',
    ];

    public function lineGroups()
    {
        return $this->hasMany(MstrLineGroup::class, 'Lg_slocId', 'Tp_mtCode');
    }
}