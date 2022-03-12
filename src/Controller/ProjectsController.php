<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Manager\ProjectManager;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class ProjectsController extends AbstractController
{
    public function __construct(
        private ProjectManager $projectManager,
        private ProjectRepository $projectRepository
    ) {
    }
    #[Route('/projects', name: 'projects_homepage')]
    public function listProjects(PaginatorInterface $paginatorInterface, ProjectRepository $projectRepository, Request $request): Response
    {
        $projects = $paginatorInterface->paginate(
            $projectRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('projects/projects.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/projects/add', name: 'projects_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->projectManager->flashAddErrorMessage();
            return $this->redirectToRoute('projects_add');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectManager->addProject($project);
            return $this->redirectToRoute('projects_add');
        }

        return $this->render('projects/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/projects/details/{id}', name: 'projects_details', requirements: ['id' => '\d+'])]
    public function details(int $id): Response
    {
        $projectDetails = $this->projectRepository->find($id);
        return $this->render('projects/details.html.twig', [
            'project' => $projectDetails
        ]);
    }



    #[Route('/projects/delete/{id}', name: 'projects_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id = null): Response
    {

        if ($id !== null) {
            $this->projectManager->deleteProject($id);
            return $this->redirectToRoute('projects_homepage');
        } else {
            $this->projectManager->flashDeleteErrorMessage();
            return $this->redirectToRoute('projects_homepage');
        }
    }
}
