<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
	private $id;

	#[ORM\Column(type: Types::STRING, length: 255)]
	private $name;

	#[ORM\Column(type: Types::TEXT)]
	private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getName(): string
   	{
   		return $this->name;
   	}

	public function setName(string $name): self
   	{
   		$this->name = $name;
   		return $this;
   	}

	public function getValue(): mixed
   	{
   		return unserialize($this->value);
   	}

	public function setValue(mixed $value): self
   	{
   		$this->value = serialize($value);
   		return $this;
   	}

}
