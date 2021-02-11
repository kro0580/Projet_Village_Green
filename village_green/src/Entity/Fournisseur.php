<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fournisseur
 *
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity
 */
class Fournisseur
{
    /**
     * @var int
     *
     * @ORM\Column(name="four_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fourId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="four_nom", type="string", length=50, nullable=true)
     */
    private $fourNom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="four_type", type="string", length=50, nullable=true)
     */
    private $fourType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", inversedBy="envFour")
     * @ORM\JoinTable(name="envoie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="env_four_id", referencedColumnName="four_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="env_pro_id", referencedColumnName="pro_id")
     *   }
     * )
     */
    private $envPro;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->envPro = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getFourId(): ?int
    {
        return $this->fourId;
    }

    public function getFourNom(): ?string
    {
        return $this->fourNom;
    }

    public function setFourNom(?string $fourNom): self
    {
        $this->fourNom = $fourNom;

        return $this;
    }

    public function getFourType(): ?string
    {
        return $this->fourType;
    }

    public function setFourType(?string $fourType): self
    {
        $this->fourType = $fourType;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getEnvPro(): Collection
    {
        return $this->envPro;
    }

    public function addEnvPro(Produit $envPro): self
    {
        if (!$this->envPro->contains($envPro)) {
            $this->envPro[] = $envPro;
        }

        return $this;
    }

    public function removeEnvPro(Produit $envPro): self
    {
        $this->envPro->removeElement($envPro);

        return $this;
    }

}
