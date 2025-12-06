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
     * The attributes that are mass assignable.
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
            'password' => 'hashed',
            'is_active' =>'boolean',
        ];
    }

    // metoda pentru dezactivarea conturilor expirate
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

    public function clients()
    {
        return $this->hasMany(Client::class);
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
