<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');


Route::middleware('auth:api')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    Route::middleware('role:étudiant')->group(function () {
        Route::get('/recommendations', [CourseController::class, 'recommendations']);
        Route::get('/my-enrolled-courses', [EnrollmentController::class, 'myEnrolledCourses']);
        Route::post('/courses/{id}/favorite', [CourseController::class, 'toggleFavorite']);
        Route::get('/favorites', [CourseController::class, 'favorites']);


        Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'store']); // + Stripe Token
        Route::delete('/courses/{id}/withdraw', [EnrollmentController::class, 'destroy']);
    });

    Route::middleware('role:enseignant')->group(function () {
        Route::get('/my-courses', [CourseController::class, 'myCourses']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
        Route::get('/categories', [CategoryController::class, 'index']);

        Route::get('/my-courses/{id}/students', [EnrollmentController::class, 'courseStudents']);
        Route::get('/my-stats', [EnrollmentController::class, 'stats']);
        Route::get('/courses/{id}/groups', [GroupController::class, 'index']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
