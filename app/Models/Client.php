<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Asigură-te că importi modelul Vehicle dacă e nevoie (de obicei Laravel îl găsește singur dacă e în același folder)

class Client extends Model
{
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }


    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
