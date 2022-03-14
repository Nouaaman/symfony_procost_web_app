<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\ProductionTimes;
use App\Form\EmployeeType;
use App\Form\ProductionTimesType;
use App\Manager\EmployeeManager;
use App\Manager\ProductionTimesManager;
use App\Repository\EmployeeRepository;
use App\Repository\ProductionTimesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class EmployeesController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EmployeeManager $employeeManager,
        private ProductionTimesManager $productionTimesManager,
        private ProductionTimesRepository $productionTimesRepository
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
    public function details(Request $request, int $id, PaginatorInterface $paginatorInterface): Response
    {
        if ($id == null) {
            return $this->redirectToRoute('employees_homepage');
        }

        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            $this->employeeManager->flashMessage('danger', 'Introuvable !');
            return $this->redirectToRoute('employees_homepage');
        }

        $productionTime = new ProductionTimes();
        $form = $this->createForm(ProductionTimesType::class, $productionTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->productionTimesManager->flashAddErrorMessage();
            return $this->redirectToRoute('employees_details', ['id' => $id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $productionTime->setIdEmployee($employee);
            $this->productionTimesManager->addProductionTime($productionTime);
            return $this->redirectToRoute('employees_details', ['id' => $id]);
        }

        $employeeDetails = $this->employeeRepository->findOneWithJob($id);


        $productionTimeHistory = $paginatorInterface->paginate(
            $this->productionTimesRepository->findAllByEmployee($id),
            $request->query->getInt('page', 1),
            10
        );
        dump($productionTimeHistory);
        return $this->render('employees/details.html.twig', [
            'employeeDetails' => $employeeDetails,
            'productionTimeHistory' => $productionTimeHistory,
            'form' => $form->createView()
        ]);
    }

    #[Route('/employees/delete/{id}', name: 'employees_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id = null): Response
    {
        if ($id !== null) {
            $this->employeeManager->deleteEmployee($id);
            return $this->redirectToRoute('employees_homepage');
        } else {
            $this->employeeManager->flashDeleteErrorMessage();
            return $this->redirectToRoute('employees_homepage');
        }
    }


    #[Route('/employees/edit/{id}', name: 'employees_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id = null, Request $request): Response
    {

        if ($id == null) {
            return $this->redirectToRoute('employees_homepage');
        }

        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            $this->employeeManager->flashMessage('danger', 'Introuvable !');
            return $this->redirectToRoute('employees_homepage');
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->employeeManager->flashEditErrorMessage();
            return $this->redirectToRoute('employees_edit', ['id' => $id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->employeeManager->editEmployee($employee);
            return $this->redirectToRoute('employees_edit', ['id' => $id]);
        }

        return $this->render('employees/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
