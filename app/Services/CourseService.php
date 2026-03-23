<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    protected $courseRepo;

    public function __construct(CourseRepositoryInterface $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function getAllCourses()
    {
        return $this->courseRepo->all();
    }

    public function getCourseDetails(int $id)
    {
        return $this->courseRepo->findById($id);
    }

    public function createCourse(array $data)
    {
        $data['teacher_id'] = Auth::id(); // L'enseignant connecté
        return $this->courseRepo->create($data);
    }

    public function updateCourse(int $id, array $data)
    {
        $course = $this->courseRepo->findById($id);


        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception("Vous n'êtes pas autorisé à modifier ce cours.");
        }

        return $this->courseRepo->update($id, $data);
    }

    public function deleteCourse(int $id)
    {
        $course = $this->courseRepo->findById($id);

        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception("Vous n'êtes pas autorisé à supprimer ce cours.");
        }

        return $this->courseRepo->delete($id);
    }

    public function getRecommendations()
    {
        $user = auth()->user();

        if (empty($user->interests)) {
            return $this->courseRepo->all();
        }

        return $this->courseRepo->getByCategories($user->interests);
    }
}
