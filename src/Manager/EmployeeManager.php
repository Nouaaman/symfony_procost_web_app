<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class EmployeeManager
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
        private EmployeeRepository $employeeRepository,
    ) {
    }

    public function deleteEmployee(int $id)
    {
        try {
            $job = $this->employeeRepository->find($id);
            $this->em->remove($job);
            $this->em->flush();
            $this->flashDeleteSuccessMessage();
        } catch (\Throwable $error) {
            if ($error->getCode() == 1451) {
                $this->flashMessage('danger', 'Cet employé est attribué a un projet !');
            } else {
                $this->flashMessage('danger', 'Erreur de suppression!');
            }
        }
    }
    public function addEmployee(Employee $employee)
    {
        $this->em->persist($employee);
        $this->em->flush();
        $this->flashAddSuccessMessage();
    }

    public function editEmployee(Employee $employee)
    {
        $this->em->persist($employee);
        $this->em->flush();
        $this->flashEditSuccessMessage();
    }

    public function flashAddSuccessMessage()
    {
        $this->flashMessage('success', "l'employé a bien été enregistré !");
    }
    public function flashAddErrorMessage()
    {
        $this->flashMessage('danger', 'Erreur !');
    }

    public function flashDeleteSuccessMessage()
    {
        $this->flashMessage('success', "l'employé a bien été supprimé !");
    }
    public function flashDeleteErrorMessage()
    {
        $this->flashMessage('danger', "l'employé n'a pas été supprimé !");
    }

    public function flashEditSuccessMessage()
    {
        $this->flashMessage('success', 'Modification reussi !');
    }
    public function flashEditErrorMessage()
    {
        $this->flashMessage('danger', "l'employé a pas été modifié !");
    }

    public function flashMessage(string $type, string $message)
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();
        $flashBag->add($type, $message);
    }
}
