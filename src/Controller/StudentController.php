<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use App\Repository\CourseRepository;
use App\Repository\ModuleRepository;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StudentController extends AbstractController
{
    #[Route('/students', name: 'app_student')]
    public function index(
        CourseRepository $courseRepository,
        StudentRepository $studentRepository,
        ModuleRepository $moduleRepository,
        EntityManagerInterface $em,
    ): Response {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['nom'] ?? null;
            $matricule = $_POST["matricule"] ?? null;
            $prenom = $_POST["prenom"] ?? null;
            $action = $_POST["action"] ?? null;
            $id = $_POST["id"] ?? null;

            if ($nom && $matricule && $prenom) {
                if ($action && $action == "edit" && $id) {
                    $student = $studentRepository->find($id);
                    if ($student) {
                        $student->setNom($nom);
                        $student->setPrenom($prenom);
                        $student->setMatricule($matricule);
                        $em->flush();
                    }
                } else if (count($studentRepository->findBy(["nom" => $nom])) == 0) {
                    $student = new Student();
                    $student->setNom($nom);
                    $student->setPrenom($prenom);
                    $student->setMatricule($matricule);
                    $em->persist($student);
                    $em->flush();
                }
            } else if ($action && $id) {
                if ($action == "delete") {
                    $student = $studentRepository->find($id);
                    $em->remove($student);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('app_student');
        } else {
            $course_count = $courseRepository->count();
            $students = $studentRepository->findAll();
            $module_count = $moduleRepository->count();
            return $this->render('student/index.html.twig', [
                'controller_name' => 'StudentController',
                'courses' => $course_count,
                'students' => $students,
                'modules' => $module_count,
            ]);
        }
    }
}
