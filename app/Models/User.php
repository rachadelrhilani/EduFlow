<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'interests'
    ];

    public function coursesAsTeacher() {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Si Étudiant : Inscriptions aux cours
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    // Si Étudiant : Cours favoris
    public function favorites() {
        return $this->belongsToMany(Course::class, 'favorites');
    }

    // Appartenance aux groupes (pivot)
    public function groups() {
        return $this->belongsToMany(Group::class, 'group_user');
    }

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
            'interests' => 'array',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
