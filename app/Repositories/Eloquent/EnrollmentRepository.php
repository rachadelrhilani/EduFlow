<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EnrollmentRepository implements EnrollmentRepositoryInterface {
    public function enroll($userId, $courseId, $paymentId) {
        return Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'payment_status' => 'paid',
            'stripe_id' => $paymentId
        ]);
    }

    public function withdraw($userId, $courseId) {
        return Enrollment::where('user_id', $userId)->where('course_id', $courseId)->delete();
    }

    public function getStudentsByCourse($courseId) {
        return Course::find($courseId)->enrollments()->with('user')->get();
    }

    public function getTeacherStats(int $teacherId) {
        return [
            // 1. Nombre total de cours créés par ce prof
            'total_courses' => Course::where('teacher_id', $teacherId)->count(),

            // 2. Nombre total d'étudiants inscrits (somme de toutes les inscriptions à ses cours)
            'total_enrollments' => Enrollment::whereHas('course', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->count(),

            // 3. Revenu total généré (Somme des prix des cours vendus)
            'total_revenue' => DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('courses.teacher_id', $teacherId)
                ->sum('courses.price'),
                
            // 4. Détails par cours (Optionnel mais très utile pour un Dashboard)
            'courses_summary' => Course::where('teacher_id', $teacherId)
                ->withCount('enrollments')
                ->get(['id', 'title', 'price'])
        ];
    }
}