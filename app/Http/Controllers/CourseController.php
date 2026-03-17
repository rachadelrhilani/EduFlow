<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller {
    protected $courseService;

    public function __construct(CourseService $courseService) {
        $this->courseService = $courseService;
    }

    public function index() {
        return response()->json($this->courseService->getAllCourses());
    }

    public function show($id) {
        return response()->json($this->courseService->getCourseDetails($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        return response()->json($this->courseService->createCourse($data), 201);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
        ]);

        try {
            $course = $this->courseService->updateCourse($id, $data);
            return response()->json($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function destroy($id) {
        try {
            $this->courseService->deleteCourse($id);
            return response()->json(['message' => 'Cours supprimé']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}