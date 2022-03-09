<?php

namespace App\Entity;

use App\Repository\ProductionTimesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductionTimesRepository::class)]
class ProductionTimes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $productionTime;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'time')]
    private $idEmployee;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'time')]
    private $idProject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductionTime(): ?int
    {
        return $this->productionTime;
    }

    public function setProductionTime(int $productionTime): self
    {
        $this->productionTime = $productionTime;

        return $this;
    }

    public function getIdEmployee(): ?Employee
    {
        return $this->idEmployee;
    }

    public function setIdEmployee(?Employee $idEmployee): self
    {
        $this->idEmployee = $idEmployee;

        return $this;
    }

    public function getIdProject(): ?Project
    {
        return $this->idProject;
    }

    public function setIdProject(?Project $idProject): self
    {
        $this->idProject = $idProject;

        return $this;
    }
}
