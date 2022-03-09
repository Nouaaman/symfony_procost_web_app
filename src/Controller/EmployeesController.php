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
    // public function __construct(
    //     private EmployeeRepository $employeeRepository,
    // ) {
    // }
    #[Route('/employees', name: 'employees_homepage')]
    public function listEmployees(PaginatorInterface $paginatorInterface, EmployeeRepository $employeeRepository, Request $request): Response
    {
        $employees = $paginatorInterface->paginate(
            $employeeRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('employees/employees.html.twig', [
            'employees' => $employees
        ]);
    }
}
