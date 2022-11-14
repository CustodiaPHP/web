<?php

namespace App\Entity;

use App\Repository\ServiceLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceLogRepository::class)]
class ServiceLog
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
	private $id;

	#[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'serviceLogs')]
	#[ORM\JoinColumn(nullable: false)]
    private $service;

	#[ORM\Column(type: Types::INTEGER)]
    private $status;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private $timestamp;

	#[ORM\Column(type: Types::FLOAT)]
    private $responseTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

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

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getResponseTime(): ?float
    {
        return $this->responseTime;
    }

    public function setResponseTime(float $responseTime): self
    {
        $this->responseTime = $responseTime;

        return $this;
    }
}
