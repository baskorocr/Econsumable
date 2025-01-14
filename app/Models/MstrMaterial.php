<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrMaterial extends Model
{
    use HasFactory;

    protected $table = 'mstr_materials';
    protected $primaryKey = 'Mt_number';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Mt_number',
        'Mt_lgId',
        'Mt_desc'
    ];

    public function masterLineGroup()
    {
        return $this->belongsTo(MstrLineGroup::class, 'Mt_lgId', 'Lg_code');
    }

    public function consumables()
    {
        return $this->hasMany(MstrConsumable::class, 'Cb_mtId', 'Mt_number');
    }

}