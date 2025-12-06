<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'method',
        'objective',
        'notes',
        'appointment_date',
        'is_completed',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}
