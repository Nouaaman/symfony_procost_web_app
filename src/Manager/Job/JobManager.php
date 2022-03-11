<?php

declare(strict_types=1);

namespace App\Manager\Job;

use App\Entity\Job;
use App\Event\Job\JobAdded;
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
            $this->flashDeleteErrorMessage();
        }
    }
    public function addJob(Job $job)
    {
        $this->em->persist($job);
        $this->em->flush();
        $this->flashAddSuccessMessage();
    }

    public function flashAddSuccessMessage()
    {
        $this->flashMessage('success', 'le Métier a bien été enregistré !');
    }
    public function flashAddErrorMessage()
    {
        $this->flashMessage('danger', 'Erreur !');
    }

    public function flashDeleteSuccessMessage()
    {
        $this->flashMessage('success', 'le Métier a bien été supprimé !');
    }
    public function flashDeleteErrorMessage()
    {
        $this->flashMessage('danger', "le Métier n'a pas été supprimé !");
    }
    private function flashMessage(string $type, string $message)
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();
        $flashBag->add($type, $message);
    }
}
