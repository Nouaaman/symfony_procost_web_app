<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects_homepage')]
    public function listProjects(PaginatorInterface $paginatorInterface, ProjectRepository $projectRepository, Request $request): Response
    {
        $projects = $paginatorInterface->paginate(
            $projectRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('projects/projects.html.twig', [
            'projects'=>$projects
        ]);
    }
}
