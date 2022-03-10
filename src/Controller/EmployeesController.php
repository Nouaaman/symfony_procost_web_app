<?php

namespace App\Controller;


use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class EmployeesController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
    ) {
    }

    #[Route('/employees', name: 'employees_homepage')]
    public function listEmployees(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $employeesData = $paginatorInterface->paginate(
            $this->employeeRepository->findAllWithJob(),
            $request->query->getInt('page', 1),
            10
        );
        dump($employeesData);
        return $this->render('employees/employees.html.twig', [
            'employeesData' => $employeesData
        ]);
    }

    #[Route('/employees/details/{id}', name: 'employee_details', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employeeDetails(Request $request, int $id): Response
    {
        $employeeDetails = $this->employeeRepository->findOneWithJob($id);
        return $this->render('employees/details.html.twig', [
            'employeeDetails' => $employeeDetails
        ]);
    }
}
