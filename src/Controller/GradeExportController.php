<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;

final class GradeExportController extends AbstractController
{
    #[Route('/bulletin/{id}', name: 'app_grade_export', methods: ["GET"])]
    public function index(int $id, StudentRepository $studentRepository, CourseRepository $courseRepository): Response
    {
        if ($id == null) {
            return $this->redirectToRoute('app_student');
        }
        $student = $studentRepository->find($id);
        $notes = $studentRepository->find($id)->getNotes();
        $courseMap = [];
        foreach ($notes as $note) {
            if (!isset($courseMap[$note->getId()])) {
                $courseMap[$note->getId()] = $courseRepository->find($note->getCourseId());
            }
        }
        $html = $this->renderView('grade_export/index.html.twig', [
            'grades' => $notes,
            'student' => $student,
            'courses' => $courseMap
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="export.pdf"',
        ]);
    }
}
