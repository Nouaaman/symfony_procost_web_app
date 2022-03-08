<?php

namespace App\Entity;

use App\Repository\EmployeeProjectsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeProjectsRepository::class)]
class EmployeeProjects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: 'id', nullable: true)]
    private ?int $idEmployee = null;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'id', nullable: true)]
    private ?int $idProject = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEmployee(): ?int
    {
        return $this->idEmployee;
    }

    public function setIdEmployee(int $idEmployee): self
    {
        $this->idEmployee = $idEmployee;

        return $this;
    }

    public function getIdProject(): ?int
    {
        return $this->idProject;
    }

    public function setIdProject(int $idProject): self
    {
        $this->idProject = $idProject;

        return $this;
    }
}
