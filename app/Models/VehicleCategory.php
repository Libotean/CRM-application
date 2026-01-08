<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Specificăm explicit tabelul (ca să fim siguri)
    protected $table = 'vehicles';

    protected $guarded = [];

    // RELAȚIA CU MARCA
    public function make()
    {
        // Parametrul 2 ('vehicle_make_id') este CRITIC. Fără el, Laravel caută 'vehicle_make_id' și nu găsește.
        // Așteaptă... Laravel caută implicit 'vehicle_make_id' doar dacă funcția se numea 'vehicleMake'.
        // Dar funcția ta se numește 'make', deci Laravel caută implicit 'make_id'.
        // De aceea TREBUIE să scriem 'vehicle_make_id' aici:
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    // RELAȚIA CU MODELUL
    public function model()
    {
        // La fel și aici: specificăm coloana ta din baza de date
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    // RELAȚIA CU CLIENTUL
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
