<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'firstname',
        'email',
        'phone',
        'status',
        'function',
        'department',
        'service',
        'matricule',
        'photo',
        'password',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function ayantsDroit()
    {
        return $this->hasMany(AyantDroit::class, 'fonctionnaire_user_id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'demandeur_user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Scopes
     */
    public function scopeActifs($query)
    {
        return $query->where('status', 'actif');
    }

    /**
     * Accessors
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->name}";
    }
}
