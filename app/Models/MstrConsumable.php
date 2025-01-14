<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrConsumable extends Model
{
    use HasFactory;

    protected $table = 'mstr_consumables';
    protected $primaryKey = 'Cb_number';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'Cb_number',
        'Cb_mtId',
        'Cb_desc',

    ];

    public function material()
    {
        return $this->belongsTo(MstrMaterial::class, 'Cb_mtId', 'Mt_number');
    }





}