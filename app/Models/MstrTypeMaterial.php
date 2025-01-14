<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrTypeMaterial extends Model
{
    use HasFactory;

    protected $table = 'mstr_type_materials';

    protected $fillable = [
        'Ty_desc'
    ];
}