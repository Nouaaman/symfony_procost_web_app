<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects_homepage')]
    public function index(): Response
    {
        return $this->render('projects/projects.html.twig', []);
    }
}
