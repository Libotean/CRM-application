<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
    * Atributele care pot fi atribuite.
    * Include campuri specifice CRM-ului:
    * - role: 'admin' sau 'user'
    * - date_end: Data la care expira accesul utilizatorului
    * - is_active: Starea contului
    *
    * @var list<string>
    */
    protected $fillable = [
        'firstname',
        'lastname',
        'country',
        'county',
        'locality',
        'email',
        'password',
        'date_start',
        'date_end',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed', // hash automat la setarea parolei
            'is_active' =>'boolean',
        ];
    }

    /**
    * Metoda de mentenanta a conturilor.
    * Aceasta functie este apelata pentru a verifica
    * daca exista utilizatori activi a caror data de expirare a trecut.
    * Daca gaseste astfel de cazuri, le dezactiveaza automat contul.
    * @return void
    */
    public static function updateExpiredStatus(): void 
    {
        $users = self::where('is_active', true)
                    ->whereNotNull('date_end')
                    ->get();
        
        foreach ($users as $user) {
            if ($user->date_end && \Carbon\Carbon::parse($user->date_end)->isPast()) {
                $user->update(['is_active' => false]);
            }
        }
    }

    /**
    * Relatie One-to-Many cu modelul Client.
    * Un utilizator poate avea in portofoliu mai multi Clienti.
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
    * Relatie One-to-Many cu modelul Lead.
    * Un utilizator poate inregistra mai multe interactiuni.
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
