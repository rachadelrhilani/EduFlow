<?php

namespace App\Providers;

use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\EnrollmentRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
        \App\Repositories\Interfaces\UserRepositoryInterface::class,
        \App\Repositories\Eloquent\UserRepository::class, 
    );
    $this->app->bind(
         CourseRepositoryInterface::class, 
        CourseRepository::class
    );
    $this->app->bind(
            EnrollmentRepositoryInterface::class, 
            EnrollmentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
