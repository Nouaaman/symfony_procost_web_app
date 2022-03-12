<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\JobType;
use App\Manager\JobManager;

class JobsController extends AbstractController
{

    public function __construct(
        private JobRepository $jobRepository,
        private JobManager $jobManager
    ) {
    }
    #[Route('/jobs', name: 'jobs_homepage')]
    public function list(PaginatorInterface $paginatorInterface, Request $request): Response
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

    #[Route('/jobs/add', name: 'jobs_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $job = new Job();

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->jobManager->flashAddErrorMessage();
            return $this->redirectToRoute('jobs_add');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobManager->addJob($job);
            return $this->redirectToRoute('jobs_add');
        }

        return $this->render('jobs/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/jobs/delete/{id}', name: 'jobs_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id = null, Request $request): Response
    {

        if ($id !== null) {
            $this->jobManager->deleteJob($id);
            return $this->redirectToRoute('jobs_homepage');
        } else {
            $this->jobManager->flashDeleteErrorMessage();
            return $this->redirectToRoute('jobs_homepage');
        }
    }


    #[Route('/jobs/edit/{id}', name: 'jobs_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id = null, Request $request): Response
    {

        if ($id == null) {
            return $this->redirectToRoute('jobs_homepage');
        }

        $job = $this->jobRepository->find($id);

        if (!$job) {
            $this->jobManager->flashMessage('danger', 'Introuvable !');
            return $this->redirectToRoute('jobs_homepage');
        }

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->jobManager->flashEditErrorMessage();
            return $this->redirectToRoute('jobs_edit',['id'=>$id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobManager->editJob($job);
            return $this->redirectToRoute('jobs_edit',['id'=>$id]);
        }

        return $this->render('jobs/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
