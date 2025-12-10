<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
     use HasFactory;
    /**
     * Atributele care pot fi completate in masa.
     */
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

    /**
     * Relatia cu modelul User.
     * Un client apartine unui utilizator (consilier).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returnare nume complet al clientului ca atribut.
     *
     * Exemplu:
     * $client->full_name
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Relatia cu modelul Lead.
     * Un client poate avea mai multe lead-uri asociate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
