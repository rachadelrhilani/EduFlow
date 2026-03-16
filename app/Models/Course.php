<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'price', 'teacher_id', 'category_id'];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }

    // Liste des étudiants ayant payé
    public function students() {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('status', 'amount');
    }
}
