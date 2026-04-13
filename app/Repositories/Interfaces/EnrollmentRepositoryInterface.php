<?php

namespace App\Repositories\Interfaces;

interface EnrollmentRepositoryInterface {
    public function enroll(int $userId, int $courseId, string $paymentId, float $amount);
    public function withdraw(int $userId, int $courseId);
    public function getUserEnrolledCourses(int $userId);
    public function getStudentsByCourse(int $courseId);
    public function getTeacherStats(int $teacherId);
}