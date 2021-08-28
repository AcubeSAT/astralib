<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
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
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $docid;

    /**
     * @ORM\OneToMany(targetEntity=Version::class, mappedBy="document", orphanRemoval=true)
     */
    private $versions;

    /**
     * @ORM\OneToMany(targetEntity=AuthorDocument::class, mappedBy="document")
     */
    private $authors;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->authors = new ArrayCollection();
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

    public function getDocid(): ?string
    {
        return $this->docid;
    }

    public function setDocid(string $docid): self
    {
        $this->docid = $docid;

        return $this;
    }

    /**
     * @return Collection|Version[]
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions[] = $version;
            $version->setDocument($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getDocument() === $this) {
                $version->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AuthorDocument[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(AuthorDocument $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->setDocument($this);
        }

        return $this;
    }

    public function removeAuthor(AuthorDocument $author): self
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getDocument() === $this) {
                $author->setDocument(null);
            }
        }

        return $this;
    }
}
