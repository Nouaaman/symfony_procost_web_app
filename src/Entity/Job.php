<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'idJob', targetEntity: Employee::class)]
    private $idEmployee;

    public function __construct()
    {
        $this->idEmployee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getidEmployee(): Collection
    {
        return $this->idEmployee;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->idEmployee->contains($employee)) {
            $this->idEmployee[] = $employee;
            $employee->setIdJob($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->idEmployee->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getIdJob() === $this) {
                $employee->setIdJob(null);
            }
        }

        return $this;
    }
}
