<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\CourseRepository;
use App\Repository\NoteRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotesController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function index(
        NoteRepository $noteRepository,
        StudentRepository $studentRepository,
        CourseRepository $courseRepository,
        EntityManagerInterface $em
    ): Response {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $noteValue = $_POST['grade'] ?? null;
            $etudId = $_POST['etud_id'] ?? null;
            $courseId = $_POST['cours'] ?? null;
            $action = $_POST['action'] ?? null;
            $id = $_POST['id'] ?? null;

            if ($noteValue !== null && $etudId && $courseId) {
                if ($action && $action == 'edit' && $id) {
                    $note = $noteRepository->find($id);
                    if ($note) {
                        $note->setNote($noteValue);
                        $note->setEtudId($studentRepository->find($etudId));
                        $note->setCourseId($courseRepository->find($courseId));
                        $em->flush();
                    }
                } else {
                    $note = new Note();
                    $note->setNote($noteValue);
                    $note->setEtudId($studentRepository->find($etudId));
                    $note->setCourseId($courseRepository->find($courseId));
                    $em->persist($note);
                    $em->flush();
                }
            } else if ($action && $id) {
                if ($action == 'delete') {
                    $note = $noteRepository->find($id);
                    if ($note) {
                        $em->remove($note);
                        $em->flush();
                    }
                }
            }

            return $this->redirectToRoute('app_notes');
        } else {
            $grades = $noteRepository->findAll();
            $students = $studentRepository->findAll();
            $courses = $courseRepository->findAll();
            $studentMap = [];
            $courseMap = [];

            foreach ($grades as $grade) {
                $gid = $grade->getId();
                if (!isset($studentMap[$gid])) {
                    $studentMap[$gid] = $studentRepository->find($grade->getEtudId());
                }
                if (!isset($courseMap[$gid])) {
                    $courseMap[$gid] = $courseRepository->find($grade->getCourseId());
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
}
