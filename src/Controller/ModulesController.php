<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ModulesController extends AbstractController
{
    #[Route('/modules', name: 'app_modules')]
    public function index(
        ModuleRepository $moduleRepository,
        EntityManagerInterface $em
    ): Response {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $moduleName = $_POST['module_name'] ?? null;
            $moduleDescription = $_POST['description'] ?? null;
            $modules = $moduleRepository->findBy(['nom' => $moduleName]);
            $action = $_POST["action"] ?? null;
            $id = $_POST["id"] ?? null;

            if ($moduleName && $moduleDescription) {
                if ($action && $action == "edit" && $id) {
                    $module = $moduleRepository->find($id);
                    if ($module) {
                        $module->setNom($moduleName);
                        $module->setDescription($moduleDescription);
                        $em->flush();
                    }
                } else if (count($modules) == 0) {
                    $module = new Module();
                    $module->setNom($moduleName);
                    $module->setDescription($moduleDescription);

                    $em->persist($module);
                    $em->flush();
                }
            } else if ($action && $id) {
                if ($action == "delete") {
                    $module = $moduleRepository->find($id);
                    $em->remove($module);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('app_modules');
        }
        $modules = $moduleRepository->findAll();
        return $this->render('modules/index.html.twig', [
            'controller_name' => 'ModulesController',
            'modules' => $modules,
        ]);
    }
}
