<?php

namespace App\Providers;

use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\EnrollmentRepository;
use App\Repositories\Eloquent\GroupRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );
        $this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );
        $this->app->bind(
            EnrollmentRepositoryInterface::class,
            EnrollmentRepository::class
        );
        $this->app->bind(
            GroupRepositoryInterface::class,
            GroupRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            // On construit l'URL en dur vers la route WEB
            // On utilise config('app.url') pour que ça s'adapte à ton .env
            return config('app.url') . '/reset-password/' . $token . '?email=' . urlencode($user->email);
        });
    }
}
