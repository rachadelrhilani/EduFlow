<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'course_id', 'max_capacity'];

    protected $attributes = [
        'max_capacity' => 25,
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function students() {
        return $this->belongsToMany(User::class, 'group_user');
    }

    
    public function isFull() {
        return $this->students()->count() >= $this->max_capacity;
    }
}
