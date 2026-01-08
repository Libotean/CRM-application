<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    // Specificăm explicit tabelul
    protected $table = 'vehicle_models';

    protected $guarded = [];

    // Opțional: Relația inversă spre Marcă (poate ai nevoie pe viitor)
    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'make_id');
    }
}
