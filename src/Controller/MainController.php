<?php

namespace App\Controller;


use App\Repository\EmployeeRepository;
use App\Repository\ProductionTimesRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    public function __construct(
        private ProjectRepository $projectRepository,
        private EmployeeRepository $employeeRepository,
        private ProductionTimesRepository $ProductionTimesRepository,
    ) {
    }

    #[Route('/', name: 'main_homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'nbrCurrentProjects' => $this->projectRepository->nbrCurrentProjects(),
            'nbrDeliveredProjects' => $this->projectRepository->nbrDeliveredProjects(),
            'nbrEmployees' => $this->employeeRepository->nbrEmployees(),
            'productionDays' => $this->ProductionTimesRepository->productionDays(),
            'latestProjects' => $this->projectRepository->theLatestProjects(),
            'latestProductionTimes' => $this->ProductionTimesRepository->latestProductionTimes(),
        ]);
    }
}
