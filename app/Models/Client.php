<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "clients";
    protected $fillable = [
            'user_id',
            'type',
            'firstname',
            'lastname',
            'cnp',
            'cui',
            'tva_payer',
            'email',
            'phone',
            'country',
            'county',
            'locality',
            'address',
            'status',
    ];

}
