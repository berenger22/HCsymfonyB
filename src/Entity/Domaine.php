<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DomaineRepository")
 */
class Domaine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="domaine", orphanRemoval=true)
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Domaine", inversedBy="domaines")
     */
    private $domaine;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Domaine", mappedBy="domaine")
     */
    private $domaines;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->domaine = new ArrayCollection();
        $this->domaines = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setDomaine($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getDomaine() === $this) {
                $question->setDomaine(null);
            }
        }

        return $this;
    }

    public function getDomaine(): ?self
    {
        return $this->domaine;
    }

    public function setDomaine(?self $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function addDomaine(self $domaine): self
    {
        if (!$this->domaine->contains($domaine)) {
            $this->domaine[] = $domaine;
            $domaine->setDomaine($this);
        }

        return $this;
    }

    public function removeDomaine(self $domaine): self
    {
        if ($this->domaine->contains($domaine)) {
            $this->domaine->removeElement($domaine);
            // set the owning side to null (unless already changed)
            if ($domaine->getDomaine() === $this) {
                $domaine->setDomaine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }
}
