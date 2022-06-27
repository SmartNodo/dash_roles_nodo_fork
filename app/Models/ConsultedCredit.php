<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultedCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'idState_state',
        'user_id',
        'nss',
        'creditNumber',
        'status',
        'balance',
        'email',
        'description',
        'isError',
        'consultedDate',
        'confirmedDate'
    ];

}
