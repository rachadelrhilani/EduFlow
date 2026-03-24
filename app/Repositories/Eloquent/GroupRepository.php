<?php
namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\Interfaces\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface{
public function getOrCreateAvailableGroup(int $courseId)
    {
        // Chercher un groupe existant pour ce cours qui n'est pas plein (< 25)
        $group = Group::where('course_id', $courseId)
            ->withCount('students')
            ->having('students_count', '<', 25)
            ->first();

        // Si aucun groupe n'est disponible, on en crée un nouveau
        if (!$group) {
            $nextNumber = Group::where('course_id', $courseId)->count() + 1;
            $group = Group::create([
                'name' => "Groupe " . $nextNumber,
                'course_id' => $courseId,
                'max_capacity' => 25
            ]);
        }

        return $group;
    }

    public function getGroupsByCourse(int $courseId)
    {
        // Recupere les groupes avec les participants (relation 'students' de ton modèle Group)
        return Group::where('course_id', $courseId)
            ->with('students:id,name,email') 
            ->withCount('students')
            ->get();
    }

    public function addStudentToGroup(int $groupId, int $userId)
    {
        $group = Group::findOrFail($groupId);
        return $group->students()->attach($userId);
    }
}