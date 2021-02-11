<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 *
 * @ORM\Table(name="adresse", indexes={@ORM\Index(name="IDX_C35F0816C7440455", columns={"adr_cli_id"})})
 * @ORM\Entity
 */
class Adresse
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
     * @ORM\Column(name="adr_num_rue", type="string", length=255, nullable=false)
     */
    private $adrNumRue;

    /**
     * @var string
     *
     * @ORM\Column(name="adr_ville", type="string", length=255, nullable=false)
     */
    private $adrVille;

    /**
     * @var string
     *
     * @ORM\Column(name="adr_pays", type="string", length=255, nullable=false)
     */
    private $adrPays;

    /**
     * @var string
     *
     * @ORM\Column(name="adr_cp", type="string", length=255, nullable=false)
     */
    private $adrCp;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="cli_adresses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(nullable=false, name="adr_cli_id", referencedColumnName="cli_id")
     * })
     */
    private $adr_cli_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(){
        return $this->getAdrNumRue().'[br]'.$this->getAdrCp().'[br]'.$this->getAdrVille().'-'.$this->getAdrPays();
    }

    public function getAdrNumRue(): ?string
    {
        return $this->adrNumRue;
    }

    public function setAdrNumRue(string $adrNumRue): self
    {
        $this->adrNumRue = $adrNumRue;

        return $this;
    }

    public function getAdrVille(): ?string
    {
        return $this->adrVille;
    }

    public function setAdrVille(string $adrVille): self
    {
        $this->adrVille = $adrVille;

        return $this;
    }

    public function getAdrPays(): ?string
    {
        return $this->adrPays;
    }

    public function setAdrPays(string $adrPays): self
    {
        $this->adrPays = $adrPays;

        return $this;
    }

    public function getAdrCp(): ?string
    {
        return $this->adrCp;
    }

    public function setAdrCp(string $adrCp): self
    {
        $this->adrCp = $adrCp;

        return $this;
    }

    public function getAdrCliId(): ?Client
    {
        return $this->adr_cli_id;
    }

    public function setAdrCliId(?Client $adr_cli_id): self
    {
        $this->adr_cli_id = $adr_cli_id;

        return $this;
    }


}
