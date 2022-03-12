<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class JobManager
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
        private JobRepository $jobRepository
    ) {
    }

    public function deleteJob(int $id)
    {
        try {
            $job = $this->jobRepository->find($id);
            $this->em->remove($job);
            $this->em->flush();
            $this->flashDeleteSuccessMessage();
        } catch (\Throwable $error) {
            if ($error->getCode() == 1451) {
                $this->flashMessage('danger', 'Ce métier est attribué a un employé !');
            } else {
                $this->flashMessage('danger', 'Erreur de suppression!');
            }
        }
    }
    public function addJob(Job $job)
    {
        $this->em->persist($job);
        $this->em->flush();
        $this->flashAddSuccessMessage();
    }

    public function editJob(Job $job)
    {
        $this->em->persist($job);
        $this->em->flush();
        $this->flashEditSuccessMessage();
    }

    public function flashAddSuccessMessage()
    {
        $this->flashMessage('success', 'le métier a bien été enregistré !');
    }
    public function flashAddErrorMessage()
    {
        $this->flashMessage('danger', 'Erreur !');
    }

    public function flashDeleteSuccessMessage()
    {
        $this->flashMessage('success', 'le métier a bien été supprimé !');
    }
    public function flashDeleteErrorMessage()
    {
        $this->flashMessage('danger', "le métier n'a pas été supprimé !");
    }

    public function flashEditSuccessMessage()
    {
        $this->flashMessage('success', 'Modification reussi !');
    }
    public function flashEditErrorMessage()
    {
        $this->flashMessage('danger', "le métier a pas été modifié !");
    }

    public function flashMessage(string $type, string $message)
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();
        $flashBag->add($type, $message);
    }
}
