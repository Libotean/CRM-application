<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
     use HasFactory;
    /**
     * Atributele care pot fi completate in masa.
     */
    protected $fillable = [
        'client_id',
        'user_id',
        'method',
        'objective',
        'notes',
        'appointment_date',
        'is_completed',
    ];

    /**
     * Transformari automate pentru anumite coloane.
     * - appointment_date devine instanta Carbon
     * - Carbon - format de date romanesc: dd/mm/yyyy
     * - is_completed este convertit la boolean
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    /**
     * Relatia cu modelul Client.
     * Un lead apartine unui singur client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relatia cu modelul User.
     * Un lead este gestionat de un singur consilier (user).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}
