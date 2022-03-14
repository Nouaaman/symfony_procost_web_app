<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\ProductionTimes;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductionTimesManager
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function addProductionTime(ProductionTimes $productionTime)
    {
        $this->em->persist($productionTime);
        $this->em->flush();
        $this->flashAddSuccessMessage();
    }



    public function flashAddSuccessMessage()
    {
        $this->flashMessage('success', 'Temps de production ajouté !');
    }
    public function flashAddErrorMessage()
    {
        $this->flashMessage('danger', 'Erreur !');
    }



    public function flashMessage(string $type, string $message)
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();
        $flashBag->add($type, $message);
    }
}
