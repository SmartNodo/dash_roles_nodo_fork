<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $primaryKey = 'idState_state';

    public function getActiveStates()
    {
        $states = $this->join('access_keys', 'states.idState', '=', 'access_keys.idState_state')
                        ->select('states.idState', 'states.name', 'access_keys.status', 'access_keys.user', 'access_keys.pass')
                        ->where('access_keys.status', '<>', 0)
                        ->where('access_keys.user', '<>', '')
                        ->where('access_keys.pass', '<>', '')
                        ->get();
        return $states;
    }
}
