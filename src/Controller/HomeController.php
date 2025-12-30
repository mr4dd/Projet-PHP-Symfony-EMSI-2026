<?php

namespace App\Controller;

use App\Repository\CourseRepository as CourseRepository;
use App\Repository\ModuleRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        CourseRepository $courseRepository,
        StudentRepository $studentRepository,
        ModuleRepository $moduleRepository
    ): Response {
        $courses = $courseRepository->findAll();
        $student_count = $studentRepository->count();
        $module_count = $moduleRepository->count();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'courses' => $courses,
            'students' => $student_count,
            'modules' => $module_count,
        ]);
    }
}
