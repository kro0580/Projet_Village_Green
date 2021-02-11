<?php

namespace App\Entity;

use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetailCommandeRepository::class)
 */
class DetailCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="cmd_det_cmd_id")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(nullable=false, name="det_cmd_cmd_id", referencedColumnName="cmd_id")
     * })
     */
    private $det_cmd_cmd_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $det_cmd_produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $det_cmd_pro_qte;

    /**
     * @ORM\Column(type="float")
     */
    private $det_cmd_pro_prix;

    /**
     * @ORM\Column(type="float")
     */
    private $det_cmd_total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetCmdCmdId(): ?Commande
    {
        return $this->det_cmd_cmd_id;
    }

    public function setDetCmdCmdId(?Commande $det_cmd_cmd_id): self
    {
        $this->det_cmd_cmd_id = $det_cmd_cmd_id;

        return $this;
    }

    public function getDetCmdProduit(): ?string
    {
        return $this->det_cmd_produit;
    }

    public function setDetCmdProduit(string $det_cmd_produit): self
    {
        $this->det_cmd_produit = $det_cmd_produit;

        return $this;
    }

    public function getDetCmdProQte(): ?int
    {
        return $this->det_cmd_pro_qte;
    }

    public function setDetCmdProQte(int $det_cmd_pro_qte): self
    {
        $this->det_cmd_pro_qte = $det_cmd_pro_qte;

        return $this;
    }

    public function getDetCmdProPrix(): ?float
    {
        return $this->det_cmd_pro_prix;
    }

    public function setDetCmdProPrix(float $det_cmd_pro_prix): self
    {
        $this->det_cmd_pro_prix = $det_cmd_pro_prix;

        return $this;
    }

    public function getDetCmdTotal(): ?float
    {
        return $this->det_cmd_total;
    }

    public function setDetCmdTotal(float $det_cmd_total): self
    {
        $this->det_cmd_total = $det_cmd_total;

        return $this;
    }
}
