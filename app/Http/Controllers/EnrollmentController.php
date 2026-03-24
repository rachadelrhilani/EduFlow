<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $enrollService;

    public function __construct(EnrollmentService $enrollService)
    {
        $this->enrollService = $enrollService;
    }

    // POST /api/courses/{id}/enroll
    public function store(Request $request, $id)
    {
        $request->validate(['stripeToken' => 'required']);

        try {
            $enrollment = $this->enrollService->enrollStudent($id, $request->stripeToken);
            return response()->json([
                'message' => 'Inscription réussie !',
                'data' => $enrollment
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function courseStudents($id)
    {
        try {
            $students = $this->enrollService->getStudentsForCourse($id);
            return response()->json([
                'course_id' => $id,
                'total_students' => $students->count(),
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    // GET /api/my-stats
    public function stats()
    {
        $stats = $this->enrollService->getStatsForTeacher();
        return response()->json($stats);
    }

    // DELETE /api/courses/{id}/withdraw
    public function destroy($id)
    {
        try {
            $deleted = $this->enrollService->cancelEnrollment($id);

            if ($deleted) {
                return response()->json([
                    'message' => 'Vous vous êtes retiré du cours avec succès.'
                ], 200);
            }

            return response()->json([
                'message' => 'Inscription introuvable.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
