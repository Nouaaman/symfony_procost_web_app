<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Manager\EmployeeManager;
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
        private EmployeeManager $employeeManager

    ) {
    }

    #[Route('/employees', name: 'employees_homepage')]
    public function list(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $employeesData = $paginatorInterface->paginate(
            $this->employeeRepository->findAllWithJob(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('employees/employees.html.twig', [
            'employeesData' => $employeesData
        ]);
    }

    #[Route('/employees/add', name: 'employees_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $employee = new Employee();

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->employeeManager->flashAddErrorMessage();
            return $this->redirectToRoute('employees_add');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->employeeManager->addEmployee($employee);
            return $this->redirectToRoute('employees_add');
        }

        return $this->render('employees/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/employees/details/{id}', name: 'employees_details', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function details(Request $request, int $id): Response
    {
        $employeeDetails = $this->employeeRepository->findOneWithJob($id);
        return $this->render('employees/details.html.twig', [
            'employeeDetails' => $employeeDetails
        ]);
    }

    #[Route('/employees/delete/{id}', name: 'employees_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id = null, Request $request): Response
    {

        if ($id !== null) {
            $this->employeeManager->deleteEmployee($id);
            return $this->redirectToRoute('employees_homepage');
        } else {
            $this->employeeManager->flashDeleteErrorMessage();
            return $this->redirectToRoute('employees_homepage');
        }
    }
}
