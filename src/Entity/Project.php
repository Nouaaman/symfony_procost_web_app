<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'float', nullable: true)]
    private $sellingPrice;

    #[ORM\Column(type: 'date')]
    private $creationDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $deliveryDate;

    #[ORM\OneToMany(mappedBy: 'idProject', targetEntity: ProductionTimes::class)]
    private $time;

    public function __construct()
    {
        $this->time = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(float $sellingPrice): self
    {
        $this->sellingPrice = $sellingPrice;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

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
            $time->setIdProject($this);
        }

        return $this;
    }

    public function removeTime(ProductionTimes $time): self
    {
        if ($this->time->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getIdProject() === $this) {
                $time->setIdProject(null);
            }
        }

        return $this;
    }
}
