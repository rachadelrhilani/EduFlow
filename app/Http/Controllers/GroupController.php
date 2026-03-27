<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    
    public function index($courseId)
    {
        try {
            $groups = $this->enrollmentService->getGroupsInfo($courseId);

            return response()->json([
                'status' => 'success',
                'course_id' => $courseId,
                'data' => $groups
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
