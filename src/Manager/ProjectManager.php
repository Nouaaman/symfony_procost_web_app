<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProjectManager
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
        private ProjectRepository $projectRepository,
    ) {
    }

    public function deleteProject(int $id)
    {
        try {
            $project = $this->projectRepository->find($id);
            $this->em->remove($project);
            $this->em->flush();
            $this->flashDeleteSuccessMessage();
        } catch (\Throwable $error) {
            if ($error->getCode() == 1451) {
                $this->flashMessage('danger', 'Cet projet est attribué a un temps de production !');
            } else {
                $this->flashMessage('danger', 'Erreur de suppression!');
            }
        }
    }
    public function addProject(Project $project)
    {
        $this->em->persist($project);
        $this->em->flush();
        $this->flashAddSuccessMessage();
    }

    public function editProject(Project $project)
    {
        $this->em->persist($project);
        $this->em->flush();
        $this->flashEditSuccessMessage();
    }

    public function flashAddSuccessMessage()
    {
        $this->flashMessage('success', "Projet a bien été enregistré !");
    }
    public function flashAddErrorMessage()
    {
        $this->flashMessage('danger', 'Erreur !');
    }

    public function flashDeleteSuccessMessage()
    {
        $this->flashMessage('success', "Projet a bien été supprimé !");
    }
    public function flashDeleteErrorMessage()
    {
        $this->flashMessage('danger', "Projet n'a pas été supprimé !");
    }

    public function flashEditSuccessMessage()
    {
        $this->flashMessage('success', 'Modification reussi !');
    }
    public function flashEditErrorMessage()
    {
        $this->flashMessage('danger', "Projet a pas été modifié !");
    }

    public function flashMessage(string $type, string $message)
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();
        $flashBag->add($type, $message);
    }
}
