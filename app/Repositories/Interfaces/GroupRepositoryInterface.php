<?php

namespace App\Repositories\Interfaces;

interface GroupRepositoryInterface
{
    public function getOrCreateAvailableGroup(int $courseId);

    public function getGroupsByCourse(int $courseId);

    public function addStudentToGroup(int $groupId, int $userId);
}