<?php

namespace App\Entity;

use App\Repository\IncidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncidentRepository::class)]
class Incident
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
	private $id;

	#[ORM\Column(type: Types::STRING, length: 255)]
    private $title;

	#[ORM\Column(type: Types::STRING, length: 255)]
    private $message;

	#[ORM\Column(type: Types::INTEGER)]
    private $status;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private $created;

	#[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'incidents')]
    private $affected;

    public function __construct()
    {
        $this->affected = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getAffected(): Collection
    {
        return $this->affected;
    }

    public function addAffected(Service $affected): self
    {
        if (!$this->affected->contains($affected)) {
            $this->affected[] = $affected;
        }

        return $this;
    }

    public function removeAffected(Service $affected): self
    {
        $this->affected->removeElement($affected);

        return $this;
    }
}
