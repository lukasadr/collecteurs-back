<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'role_id',
        'nifstat',
        'adresse',
        'tel',
        'societe',
        'notes',
        'solde',
        'type',
        'photo',
        'email',
        'password',
        'latitude',
        'longitude'
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        return asset($this->photo);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function prets()
    {
        return $this->hasMany(Pret::class);
    }

    public function machines()
    {
        return $this->belongsToMany(Machine::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }
}
