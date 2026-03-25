<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;
use Illuminate\Support\Facades\Auth;

class EnrollmentService
{
    protected $enrollRepo;
    protected $courseRepo;
    protected $groupRepo;

    public function __construct(
        EnrollmentRepositoryInterface $enrollRepo,
        CourseRepositoryInterface $courseRepo,
        GroupRepositoryInterface $groupRepo
    ) {
        $this->enrollRepo = $enrollRepo;
        $this->courseRepo = $courseRepo;
        $this->groupRepo = $groupRepo;
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

            $enrollment = $this->enrollRepo->enroll($userId, $courseId, $charge->id, $course->price);
            
            $group = $this->groupRepo->getOrCreateAvailableGroup($courseId);
            $this->groupRepo->addStudentToGroup($group->id, $userId);

            // retour des données pour le contrôleur
            return [
                'enrollment' => $enrollment,
                'group_assigned' => $group->name,
                'current_capacity' => $group->students()->count()
            ];
        } catch (Exception $e) {
            throw new Exception("Échec du paiement : " . $e->getMessage());
        }
    }
    public function getGroupsInfo(int $courseId)
    {
        // On vérifie d'abord que le cours appartient bien au prof connecté
        $course = $this->courseRepo->findById($courseId);
        if ($course->teacher_id !== Auth::id()) {
            throw new Exception("Accès non autorisé à ces groupes.");
        }

        return $this->groupRepo->getGroupsByCourse($courseId);
    }

    public function cancelEnrollment(int $courseId)
    {
        return $this->enrollRepo->withdraw(Auth::id(), $courseId);
    }

    public function getStudentsForCourse(int $courseId)
    {
        $course = $this->courseRepo->findById($courseId);

        if ($course->teacher_id !== auth()->id()) {
            throw new \Exception("Vous n'êtes pas autorisé à voir les inscrits de ce cours.");
        }

        return $this->enrollRepo->getStudentsByCourse($courseId);
    }

    public function getStatsForTeacher()
    {
        return $this->enrollRepo->getTeacherStats(Auth::id());
    }
}
