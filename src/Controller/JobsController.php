<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'jobs_homepage')]
    public function index(): Response
    {
        return $this->render('jobs/jobs.html.twig', []);
    }
}
