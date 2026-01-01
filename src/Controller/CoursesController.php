<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\ModuleRepository;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(
        CourseRepository $courseRepository,
        ModuleRepository $moduleRepository,
        EntityManagerInterface $em
    ): Response {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseName = $_POST['course_name'] ?? null;
            $moduleId = $_POST['module_name'] ?? null;
            $desc = $_POST['description'] ?? null;
            $action = $_POST["action"] ?? null;
            $id = $_POST["id"] ?? null;
            $courses = $courseRepository->findBy(["nom" => $courseName]);

            if ($courseName && $moduleId && $desc) {
                if ($action && $action == "edit" && $id) {
                    $course = $courseRepository->find($id);
                    if ($course) {
                        $course->setNom($courseName);
                        $course->setModuleId($moduleRepository->find($moduleId));
                        $course->setDescription($desc);
                        $em->flush();
                    }
                } else if (count($courses) == 0) {
                    $course = new Course();
                    $course->setNom($courseName);
                    $course->setModuleId($moduleRepository->find($moduleId));
                    $course->setDescription($desc);
                    $em->persist($course);
                    $em->flush();
                }
            } else if ($action && $id) {
                if ($action == "delete") {
                    $course = $courseRepository->find($id);
                    $em->remove($course);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('app_courses');
        }
        $courses = $courseRepository->findAll();
        $modules = $moduleRepository->findAll();
        $modulesMap = [];
        foreach ($courses as $course) {
            $id = $course->getModuleId()->getId();
            if (!isset($modulesMap[$id])) {
                $modulesMap[$id] = $moduleRepository->find($id);
            }
        }
        return $this->render('courses/index.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses,
            'moduleMap' => $modulesMap,
            'modules' => $modules
        ]);
    }
}
