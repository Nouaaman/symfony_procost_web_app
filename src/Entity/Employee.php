<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email;


    #[ORM\Column(type: 'float')]
    private ?float $dailyCost;

    #[ORM\Column(type: 'date')]
    private ?\DateTime $hiringDate = null;

    #[ORM\OneToMany(mappedBy: 'idEmployee', targetEntity: ProductionTimes::class)]
    private $time;

    #[ORM\ManyToOne(targetEntity: Job::class, inversedBy: 'employees')]
    private $idJob;

    public function __construct()
    {
        $this->time = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getDailyCost(): ?float
    {
        return $this->dailyCost;
    }

    public function setDailyCost(float $dailyCost): self
    {
        $this->dailyCost = $dailyCost;

        return $this;
    }

    public function getHiringDate(): ?\DateTimeInterface
    {
        return $this->hiringDate;
    }

    public function setHiringDate(\DateTimeInterface $hiringDate): self
    {
        $this->hiringDate = $hiringDate;

        return $this;
    }

    /**
     * @return Collection<int, ProductionTimes>
     */
    public function getTime(): Collection
    {
        return $this->time;
    }

    public function addTime(ProductionTimes $time): self
    {
        if (!$this->time->contains($time)) {
            $this->time[] = $time;
            $time->setIdEmployee($this);
        }

        return $this;
    }

    public function removeTime(ProductionTimes $time): self
    {
        if ($this->time->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getIdEmployee() === $this) {
                $time->setIdEmployee(null);
            }
        }

        return $this;
    }

    public function getIdJob(): ?Job
    {
        return $this->idJob;
    }

    public function setIdJob(?Job $idJob): self
    {
        $this->idJob = $idJob;

        return $this;
    }
}
