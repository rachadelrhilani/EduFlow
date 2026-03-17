<?php
namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface {
    public function all() {
        return Course::with(['teacher', 'category'])->get();
    }

    public function findById(int $id) {
        return Course::with(['teacher', 'category', 'groups'])->findOrFail($id);
    }

    public function create(array $data) {
        return Course::create($data);
    }

    public function update(int $id, array $data) {
        $course = Course::findOrFail($id);
        $course->update($data);
        return $course;
    }

    public function delete(int $id) {
        return Course::destroy($id);
    }

    public function getByTeacher(int $teacherId) {
        return Course::where('teacher_id', $teacherId)->get();
    }
}