<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commercial
 *
 * @ORM\Table(name="commercial")
 * @ORM\Entity
 */
class Commercial
{
    /**
     * @var int
     *
     * @ORM\Column(name="com_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_nom", type="string", length=50, nullable=true)
     */
    private $comNom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_prenom", type="string", length=50, nullable=true)
     */
    private $comPrenom;

    public function getComId(): ?int
    {
        return $this->comId;
    }

    public function getComNom(): ?string
    {
        return $this->comNom;
    }

    public function setComNom(?string $comNom): self
    {
        $this->comNom = $comNom;

        return $this;
    }

    public function getComPrenom(): ?string
    {
        return $this->comPrenom;
    }

    public function setComPrenom(?string $comPrenom): self
    {
        $this->comPrenom = $comPrenom;

        return $this;
    }


}
