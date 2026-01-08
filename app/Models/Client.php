<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
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
}
