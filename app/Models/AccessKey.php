<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessKey extends Model
{
    use HasFactory;
    protected $fillable = [
        'idState_state',
        'status',
        'user',
        'pass',
        'error'
    ];
}
