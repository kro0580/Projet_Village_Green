<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity
 */
class Rubrique
{
    /**
     * @var int
     *
     * @ORM\Column(name="rub_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rubId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rub_nom", type="string", length=50, nullable=true)
     */
    private $rubNom;

    public function getRubId(): ?int
    {
        return $this->rubId;
    }

    public function getRubNom(): ?string
    {
        return $this->rubNom;
    }

    public function setRubNom(?string $rubNom): self
    {
        $this->rubNom = $rubNom;

        return $this;
    }


}
