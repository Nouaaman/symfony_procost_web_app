<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractController
{
    #[Route('/employees', name: 'employees_homepage')]
    public function index(): Response
    {
        return $this->render('employees/employees.html.twig', []);
    }
}
