<?php

namespace App\Entity;

use App\Repository\MetadataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetadataRepository::class)
 */
class Metadata
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\ManyToOne(targetEntity=Version::class, inversedBy="metadata")
     * @ORM\JoinColumn(nullable=false)
     */
    private $version;

    /**
     * @ORM\Column(type="boolean")
     */
    private $locked = false;

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

    /** @noinspection PhpMissingReturnTypeInspection */
    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() . ': ' . json_encode($this->getData());
    }
}
