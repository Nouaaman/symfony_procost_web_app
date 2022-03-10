<?php

namespace App\Controller;

use App\Repository\JobRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{

    public function __construct(
        private JobRepository $jobRepository,
    ) {
    }

    #[Route('/jobs', name: 'jobs_homepage')]
    public function listJobs(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $jobs = $paginatorInterface->paginate(
            $this->jobRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('jobs/jobs.html.twig', [
            'jobs' => $jobs
        ]);
    }
}
