<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="pro_s_rub_id", columns={"pro_s_rub_id"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="pro_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $proId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pro_lib", type="string", length=50, nullable=true)
     */
    private $proLib;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pro_descr", type="string", length=250, nullable=true)
     */
    private $proDescr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pro_prix_achat", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $proPrixAchat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pro_photo", type="string", length=250, nullable=true)
     */
    private $proPhoto;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pro_stock", type="integer", nullable=true)
     */
    private $proStock;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="pro_actif", type="boolean", nullable=true)
     */
    private $proActif;

    /**
     * @var \SousRubrique
     *
     * @ORM\ManyToOne(targetEntity="SousRubrique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pro_s_rub_id", referencedColumnName="s_rub_id")
     * })
     */
    private $proSRub;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Livraison", inversedBy="contientPro")
     * @ORM\JoinTable(name="contient",
     *   joinColumns={
     *     @ORM\JoinColumn(name="contient_pro_id", referencedColumnName="pro_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="contient_liv_id", referencedColumnName="liv_id")
     *   }
     * )
     */
    private $contientLiv;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fournisseur", mappedBy="envPro")
     */
    private $envFour;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commande", inversedBy="seComposeDePro")
     * @ORM\JoinTable(name="se_compose_de",
     *   joinColumns={
     *     @ORM\JoinColumn(name="se_compose_de_pro_id", referencedColumnName="pro_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="se_compose_de_cmd_id", referencedColumnName="cmd_id")
     *   }
     * )
     */
    private $seComposeDeCmd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contientLiv = new \Doctrine\Common\Collections\ArrayCollection();
        $this->envFour = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seComposeDeCmd = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getProId(): ?int
    {
        return $this->proId;
    }

    public function getProLib(): ?string
    {
        return $this->proLib;
    }

    public function setProLib(?string $proLib): self
    {
        $this->proLib = $proLib;

        return $this;
    }

    public function getProDescr(): ?string
    {
        return $this->proDescr;
    }

    public function setProDescr(?string $proDescr): self
    {
        $this->proDescr = $proDescr;

        return $this;
    }

    public function getProPrixAchat(): ?string
    {
        return $this->proPrixAchat;
    }

    public function setProPrixAchat(?string $proPrixAchat): self
    {
        $this->proPrixAchat = $proPrixAchat;

        return $this;
    }

    public function getProPhoto(): ?string
    {
        return $this->proPhoto;
    }

    public function setProPhoto(?string $proPhoto): self
    {
        $this->proPhoto = $proPhoto;

        return $this;
    }

    public function getProStock(): ?int
    {
        return $this->proStock;
    }

    public function setProStock(?int $proStock): self
    {
        $this->proStock = $proStock;

        return $this;
    }

    public function getProActif(): ?bool
    {
        return $this->proActif;
    }

    public function setProActif(?bool $proActif): self
    {
        $this->proActif = $proActif;

        return $this;
    }

    public function getProSRub(): ?SousRubrique
    {
        return $this->proSRub;
    }

    public function setProSRub(?SousRubrique $proSRub): self
    {
        $this->proSRub = $proSRub;

        return $this;
    }

    /**
     * @return Collection|Livraison[]
     */
    public function getContientLiv(): Collection
    {
        return $this->contientLiv;
    }

    public function addContientLiv(Livraison $contientLiv): self
    {
        if (!$this->contientLiv->contains($contientLiv)) {
            $this->contientLiv[] = $contientLiv;
        }

        return $this;
    }

    public function removeContientLiv(Livraison $contientLiv): self
    {
        $this->contientLiv->removeElement($contientLiv);

        return $this;
    }

    /**
     * @return Collection|Fournisseur[]
     */
    public function getEnvFour(): Collection
    {
        return $this->envFour;
    }

    public function addEnvFour(Fournisseur $envFour): self
    {
        if (!$this->envFour->contains($envFour)) {
            $this->envFour[] = $envFour;
            $envFour->addEnvPro($this);
        }

        return $this;
    }

    public function removeEnvFour(Fournisseur $envFour): self
    {
        if ($this->envFour->removeElement($envFour)) {
            $envFour->removeEnvPro($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getSeComposeDeCmd(): Collection
    {
        return $this->seComposeDeCmd;
    }

    public function addSeComposeDeCmd(Commande $seComposeDeCmd): self
    {
        if (!$this->seComposeDeCmd->contains($seComposeDeCmd)) {
            $this->seComposeDeCmd[] = $seComposeDeCmd;
        }

        return $this;
    }

    public function removeSeComposeDeCmd(Commande $seComposeDeCmd): self
    {
        $this->seComposeDeCmd->removeElement($seComposeDeCmd);

        return $this;
    }
}
