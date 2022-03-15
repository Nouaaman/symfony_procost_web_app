<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Manager\ProjectManager;
use App\Repository\ProjectRepository;
use DateTime;
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
    public function details(int $id = null, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        if ($id == null) {
            return $this->redirectToRoute('projects_homepage');
        }
        $projectDetails = $this->projectRepository->find($id);

        if (!$projectDetails) {
            $this->employeeManager->flashMessage('danger', 'Introuvable !');
            return $this->redirectToRoute('projects_homepage');
        }

        $employeesWorkOnProject = $paginatorInterface->paginate(
            $this->projectRepository->employeesWorkedOnProject($id),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('projects/details.html.twig', [
            'project' => $projectDetails,
            'employees' => $employeesWorkOnProject
        ]);
    }

    #[Route('/projects/delete/{id}', name: 'projects_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id = null): Response
    {
        $project = $this->projectRepository->find($id);

        if ($project->getId() !== null) {
            if ($project->getDeliveryDate() != null) {
                $this->projectManager->flashMessage('danger', 'Le projet est deja livré !');
                return $this->redirectToRoute('projects_homepage');
            }
            $this->projectManager->deleteProject($id);
            return $this->redirectToRoute('projects_homepage');
        } else {
            $this->projectManager->flashMessage('danger', 'Le projet est introuvable !');
            return $this->redirectToRoute('projects_homepage');
        }
    }

    #[Route('/projects/edit/{id}', name: 'projects_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id = null, Request $request): Response
    {

        if ($id == null) {
            return $this->redirectToRoute('projects_homepage');
        }

        $project = $this->projectRepository->find($id);

        if (!$project) {
            $this->projectManager->flashMessage('danger', 'Introuvable !');
            return $this->redirectToRoute('projects_homepage');
        } elseif ($project->getDeliveryDate() != null) {
            $this->projectManager->flashMessage('danger', 'Le projet est deja livré !');
            return $this->redirectToRoute('projects_homepage');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->projectManager->flashEditErrorMessage();
            return $this->redirectToRoute('projects_edit', ['id' => $id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $action = $form->get('delivered')->isClicked()
                ? 'delivered'
                : null;
            if ($action != null) { //set delivered
                $project->setDeliveryDate(new \DateTime);
                $this->projectManager->editProject($project);
                return $this->redirectToRoute('projects_homepage');
            }
            //update
            $this->projectManager->editProject($project);
            return $this->redirectToRoute('projects_edit', ['id' => $id]);
        }

        return $this->render('projects/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
