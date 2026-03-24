<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;
use Illuminate\Support\Facades\Auth;

class EnrollmentService
{
    protected $enrollRepo;
    protected $courseRepo;

    public function __construct(
        EnrollmentRepositoryInterface $enrollRepo,
        CourseRepositoryInterface $courseRepo
    ) {
        $this->enrollRepo = $enrollRepo;
        $this->courseRepo = $courseRepo;
    }

    public function enrollStudent(int $courseId, string $stripeToken)
    {
        $userId = Auth::id();
        $course = $this->courseRepo->findById($courseId);

        // verifier si l'étudiant est deja inscrit
        $existing = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            throw new Exception("Vous êtes déjà inscrit à ce cours.");
        }

        // configuration de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // creation de la transaction (Paiement)
            $charge = Charge::create([
                "amount" => $course->price * 100,
                "currency" => "eur",
                "source" => $stripeToken,
                "description" => "Inscription : " . $course->title . " par " . Auth::user()->email
            ]);

            // si le paiement réussit, on enregistre l'inscription via le Repository
            return $this->enrollRepo->enroll($userId, $courseId, $charge->id);
        } catch (Exception $e) {
            // si le paiement échoue (carte refusée, etc.)
            throw new Exception("Échec du paiement : " . $e->getMessage());
        }
    }

    public function cancelEnrollment(int $courseId)
    {
        return $this->enrollRepo->withdraw(Auth::id(), $courseId);
    }

    public function getStatsForTeacher()
    {
        return $this->enrollRepo->getTeacherStats(Auth::id());
    }
}
