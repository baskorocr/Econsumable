<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstrAppr extends Model
{
    use HasFactory;

    protected $table = 'mstr_apprs';
    protected $primaryKey = 'Order_no';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Order_no',
        'ConsumableId',
        'CreateNpk',
        'ApprSectDate',
        'ApprDeptDate',
        'ApprPjStokDate',


    ];
}