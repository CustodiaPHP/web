<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
	private $id;

	#[ORM\Column(type: Types::STRING, length: 255)]
    private $name;

	#[ORM\Column(type: Types::INTEGER)]
    private $type;

	#[ORM\Column(type: Types::STRING, length: 255)]
    private $address;

	#[ORM\Column(type: Types::BOOLEAN)]
    private $public;

	#[ORM\Column(type: Types::INTEGER)]
    private $currentStatus;

	#[ORM\ManyToOne(targetEntity: ServiceGroup::class, inversedBy: 'services')]
	#[ORM\JoinColumn(nullable: false)]
    private $serviceGroup;

	#[ORM\Column(type: Types::STRING, length: 255)]
    private $url;

	#[ORM\OneToMany(targetEntity: ServiceLog::class, mappedBy: 'service', orphanRemoval: true)]
    private $serviceLogs;

	#[ORM\ManyToMany(targetEntity: Incident::class, inversedBy: 'affected')]
    private $incidents;

    public function __construct()
    {
        $this->serviceLogs = new ArrayCollection();
        $this->incidents = new ArrayCollection();
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public && $this->getServiceGroup()->isPublic();
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getCurrentStatus(): ?int
    {
        return $this->currentStatus;
    }

    public function setCurrentStatus(int $currentStatus): self
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    public function getServiceGroup(): ?ServiceGroup
    {
        return $this->serviceGroup;
    }

    public function setServiceGroup(?ServiceGroup $serviceGroup): self
    {
        $this->serviceGroup = $serviceGroup;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, ServiceLog>
     */
    public function getServiceLogs(): Collection
    {
        return $this->serviceLogs;
    }

    public function addServiceLog(ServiceLog $serviceLog): self
    {
        if (!$this->serviceLogs->contains($serviceLog)) {
            $this->serviceLogs[] = $serviceLog;
            $serviceLog->setService($this);
        }

        return $this;
    }

    public function removeServiceLog(ServiceLog $serviceLog): self
    {
        if ($this->serviceLogs->removeElement($serviceLog)) {
            // set the owning side to null (unless already changed)
            if ($serviceLog->getService() === $this) {
                $serviceLog->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incident>
     */
    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incident $incident): self
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents[] = $incident;
            $incident->addAffected($this);
        }

        return $this;
    }

    public function removeIncident(Incident $incident): self
    {
        if ($this->incidents->removeElement($incident)) {
            $incident->removeAffected($this);
        }

        return $this;
    }

    public function getAverage() : float {
        $logs = $this->getServiceLogs();
        $value = 0;

        foreach ($logs as $log){
            $value += $log->getResponseTime();
        }

        return round($value / count($logs), 2);
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
