<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="passe", indexes={@ORM\Index(name="passe_cli_id", columns={"passe_cli_id"})})
 * @ORM\Entity
 */
class Passe
{
    /**
     * @var int
     *
     * @ORM\Column(name="passe_cmd_id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $passeCmdId;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\Id
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="passe_cli_id", referencedColumnName="cli_id")
     * })
     */
    private $passeCliId;
    /**
     * @return int
     */
    public function getPasseCmdId(): int
    {
        return $this->passeCmdId;
    }

    /**
     * @param int $passeCmdId
     */
    public function setPasseCmdId(int $passeCmdId): void
    {
        $this->passeCmdId = $passeCmdId;
    }

    public function getPasseCliId(): ?Client
    {
        return $this->passeCliId;
    }

    public function setPasseCliId(?Client $passeCliId): self
    {
        $this->passeCliId = $passeCliId;
        return $this;
    }

}