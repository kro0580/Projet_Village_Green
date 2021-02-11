<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousRubrique
 *
 * @ORM\Table(name="sous_rubrique", indexes={@ORM\Index(name="s_rub_rub_id", columns={"s_rub_rub_id"})})
 * @ORM\Entity
 */
class SousRubrique
{
    /**
     * @var int
     *
     * @ORM\Column(name="s_rub_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sRubId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="s_rub_nom", type="string", length=50, nullable=true)
     */
    private $sRubNom;

    /**
     * @var \Rubrique
     *
     * @ORM\ManyToOne(targetEntity="Rubrique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="s_rub_rub_id", referencedColumnName="rub_id")
     * })
     */
    private $sRubRub;

    public function getSRubId(): ?int
    {
        return $this->sRubId;
    }

    public function getSRubNom(): ?string
    {
        return $this->sRubNom;
    }

    public function setSRubNom(?string $sRubNom): self
    {
        $this->sRubNom = $sRubNom;

        return $this;
    }

    public function getSRubRub(): ?Rubrique
    {
        return $this->sRubRub;
    }

    public function setSRubRub(?Rubrique $sRubRub): self
    {
        $this->sRubRub = $sRubRub;

        return $this;
    }
    public function __toString(): ?string
    {
        return $this->getSRubNom();
    }

}
