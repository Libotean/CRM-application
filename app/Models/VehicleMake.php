<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    // Specificăm explicit tabelul
    protected $table = 'vehicle_makes';

    protected $guarded = [];
}
