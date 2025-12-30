<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\NoteRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotesController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function index(
        NoteRepository $noteRepository,
        StudentRepository $studentRepository,
        CourseRepository $courseRepository
    ): Response {
        $grades = $noteRepository->findAll();
        $students = $studentRepository->findAll();
        $courses = $courseRepository->findAll();
        $studentMap = [];
        $courseMap = [];

        foreach ($grades as $grade) {
            $id = $grade->getId();
            if (!isset($studentMap[$id])) {
                $studentMap[$id] = $studentRepository->find($grade->getEtudId());
            }
            if (!isset($courseMap[$id])) {
                $courseMap[$id] = $courseRepository->find($grade->getCourseId());
            }
        }

        return $this->render('notes/index.html.twig', [
            'controller_name' => 'NotesController',
            'grades' => $grades,
            'students' => $students,
            'courses' => $courses,
            'studentMap' => $studentMap,
            'courseMap' => $courseMap
        ]);
    }
}
