<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface
{
    public function all();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByTeacher(int $teacherId);
    public function getByCategories(array $categoryNames);
    public function toggleFavorite(int $userId, int $courseId);
    public function getUserFavorites(int $userId);
}
