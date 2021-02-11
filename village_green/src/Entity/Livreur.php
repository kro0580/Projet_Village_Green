<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livreur
 *
 * @ORM\Table(name="livreur")
 * @ORM\Entity
 */
class Livreur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="liv_nom", type="string", length=255, nullable=false)
     */
    private $livNom;

    /**
     * @var string
     *
     * @ORM\Column(name="liv_description", type="text", length=0, nullable=false)
     */
    private $livDescription;

    /**
     * @var float
     *
     * @ORM\Column(name="liv_prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $livPrix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(){
        return $this->getLivNom().'[br]'.$this->getLivDescription().'[br]'.$this->getLivPrix()/100;
    }

    public function getLivNom(): ?string
    {
        return $this->livNom;
    }

    public function setLivNom(string $livNom): self
    {
        $this->livNom = $livNom;

        return $this;
    }

    public function getLivDescription(): ?string
    {
        return $this->livDescription;
    }

    public function setLivDescription(string $livDescription): self
    {
        $this->livDescription = $livDescription;

        return $this;
    }

    public function getLivPrix(): ?float
    {
        return $this->livPrix;
    }

    public function setLivPrix(float $livPrix): self
    {
        $this->livPrix = $livPrix;

        return $this;
    }


}
