<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="liv_cmd_id", columns={"liv_cmd_id"})})
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="liv_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $livId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="liv_date", type="date", nullable=true)
     */
    private $livDate;

    /**
     * @var string
     *
     * @ORM\Column(name="liv_etat", type="string", length=250, nullable=false)
     */
    private $livEtat;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="liv_cmd_id", referencedColumnName="cmd_id")
     * })
     */
    private $livCmd;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="contientLiv")
     */
    private $contientPro;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contientPro = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getLivId(): ?int
    {
        return $this->livId;
    }

    public function getLivDate(): ?\DateTimeInterface
    {
        return $this->livDate;
    }

    public function setLivDate(?\DateTimeInterface $livDate): self
    {
        $this->livDate = $livDate;

        return $this;
    }

    public function getLivEtat(): ?string
    {
        return $this->livEtat;
    }

    public function setLivEtat(string $livEtat): self
    {
        $this->livEtat = $livEtat;

        return $this;
    }

    public function getLivCmd(): ?Commande
    {
        return $this->livCmd;
    }

    public function setLivCmd(?Commande $livCmd): self
    {
        $this->livCmd = $livCmd;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getContientPro(): Collection
    {
        return $this->contientPro;
    }

    public function addContientPro(Produit $contientPro): self
    {
        if (!$this->contientPro->contains($contientPro)) {
            $this->contientPro[] = $contientPro;
            $contientPro->addContientLiv($this);
        }

        return $this;
    }

    public function removeContientPro(Produit $contientPro): self
    {
        if ($this->contientPro->removeElement($contientPro)) {
            $contientPro->removeContientLiv($this);
        }

        return $this;
    }

}
