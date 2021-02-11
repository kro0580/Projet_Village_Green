<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="cmd_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cmdId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="cmd_date", type="date", nullable=true)
     */
    private $cmdDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_reduc", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $cmdReduc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_adresse_fact", type="string", length=50, nullable=true)
     */
    private $cmdCliAdresseFact;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_cp_fact", type="string", length=5, nullable=true, options={"fixed"=true})
     */
    private $cmdCliCpFact;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_ville_fact", type="string", length=50, nullable=true)
     */
    private $cmdCliVilleFact;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_adresse_liv", type="string", length=50, nullable=true)
     */
    private $cmdCliAdresseLiv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_cp_liv", type="string", length=5, nullable=true, options={"fixed"=true})
     */
    private $cmdCliCpLiv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_ville_liv", type="string", length=50, nullable=true)
     */
    private $cmdCliVilleLiv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cmd_cli_coeff", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $cmdCliCoeff;

    /**
     * @var bool
     *
     * @ORM\Column(name="cmd_payer", type="boolean", nullable=false)
     */
    private $cmdPayer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="passeCmd")
     * @ORM\JoinTable(name="passe",
     *   joinColumns={
     *     @ORM\JoinColumn(name="passe_cmd_id", referencedColumnName="cmd_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="passe_cli_id", referencedColumnName="cli_id")
     *   }
     * )
     */
    private $passeCli;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="seComposeDeCmd")
     */
    private $seComposeDePro;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cmd_liv_nom;

    /**
     * @ORM\Column(type="float")
     */
    private $cmd_liv_prix;

    /**
     * @ORM\OneToMany(targetEntity=DetailCommande::class, mappedBy="det_cmd_cmd_id")
     */
    private $cmd_det_cmd_id;

    /**
     * @ORM\Column(name="cmd_reference", type="string", length=255, nullable=false)
     */
    private $cmdReference;

    /**
     * @ORM\Column(name="cmd_strip_id_session", type="string", length=255, nullable=true)
     */
    private $cmdStripIdSession;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passeCli = new ArrayCollection();
        $this->seComposeDePro = new ArrayCollection();
        $this->cmd_det_cmd_id = new ArrayCollection();
    }

    public function getCmdId(): ?int
    {
        return $this->cmdId;
    }

    public function getCmdDate(): ?\DateTimeInterface
    {
        return $this->cmdDate;
    }

    public function setCmdDate(?\DateTimeInterface $cmdDate): self
    {
        $this->cmdDate = $cmdDate;

        return $this;
    }

    public function getCmdReduc(): ?string
    {
        return $this->cmdReduc;
    }

    public function setCmdReduc(?string $cmdReduc): self
    {
        $this->cmdReduc = $cmdReduc;

        return $this;
    }

    public function getCmdCliAdresseFact(): ?string
    {
        return $this->cmdCliAdresseFact;
    }

    public function setCmdCliAdresseFact(?string $cmdCliAdresseFact): self
    {
        $this->cmdCliAdresseFact = $cmdCliAdresseFact;

        return $this;
    }

    public function getCmdCliCpFact(): ?string
    {
        return $this->cmdCliCpFact;
    }

    public function setCmdCliCpFact(?string $cmdCliCpFact): self
    {
        $this->cmdCliCpFact = $cmdCliCpFact;

        return $this;
    }

    public function getCmdCliVilleFact(): ?string
    {
        return $this->cmdCliVilleFact;
    }

    public function setCmdCliVilleFact(?string $cmdCliVilleFact): self
    {
        $this->cmdCliVilleFact = $cmdCliVilleFact;

        return $this;
    }

    public function getCmdCliAdresseLiv(): ?string
    {
        return $this->cmdCliAdresseLiv;
    }

    public function setCmdCliAdresseLiv(?string $cmdCliAdresseLiv): self
    {
        $this->cmdCliAdresseLiv = $cmdCliAdresseLiv;

        return $this;
    }

    public function getCmdCliCpLiv(): ?string
    {
        return $this->cmdCliCpLiv;
    }

    public function setCmdCliCpLiv(?string $cmdCliCpLiv): self
    {
        $this->cmdCliCpLiv = $cmdCliCpLiv;

        return $this;
    }

    public function getCmdCliVilleLiv(): ?string
    {
        return $this->cmdCliVilleLiv;
    }

    public function setCmdCliVilleLiv(?string $cmdCliVilleLiv): self
    {
        $this->cmdCliVilleLiv = $cmdCliVilleLiv;

        return $this;
    }

    public function getCmdCliCoeff(): ?string
    {
        return $this->cmdCliCoeff;
    }

    public function setCmdCliCoeff(?string $cmdCliCoeff): self
    {
        $this->cmdCliCoeff = $cmdCliCoeff;

        return $this;
    }

    public function getCmdPayer(): ?bool
    {
        return $this->cmdPayer;
    }

    public function setCmdPayer(bool $cmdPayer): self
    {
        $this->cmdPayer = $cmdPayer;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getPasseCli(): Collection
    {
        return $this->passeCli;
    }

    public function addPasseCli(Client $passeCli): self
    {
        if (!$this->passeCli->contains($passeCli)) {
            $this->passeCli[] = $passeCli;
        }

        return $this;
    }

    public function removePasseCli(Client $passeCli): self
    {
        $this->passeCli->removeElement($passeCli);

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getSeComposeDePro(): Collection
    {
        return $this->seComposeDePro;
    }

    public function addSeComposeDePro(Produit $seComposeDePro): self
    {
        if (!$this->seComposeDePro->contains($seComposeDePro)) {
            $this->seComposeDePro[] = $seComposeDePro;
            $seComposeDePro->addSeComposeDeCmd($this);
        }

        return $this;
    }

    public function removeSeComposeDePro(Produit $seComposeDePro): self
    {
        if ($this->seComposeDePro->removeElement($seComposeDePro)) {
            $seComposeDePro->removeSeComposeDeCmd($this);
        }

        return $this;
    }

    public function getCmdLivNom(): ?string
    {
        return $this->cmd_liv_nom;
    }

    public function setCmdLivNom(string $cmd_liv_nom): self
    {
        $this->cmd_liv_nom = $cmd_liv_nom;

        return $this;
    }

    public function getCmdLivPrix(): ?float
    {
        return $this->cmd_liv_prix;
    }

    public function setCmdLivPrix(float $cmd_liv_prix): self
    {
        $this->cmd_liv_prix = $cmd_liv_prix;

        return $this;
    }

    /**
     * @return Collection|DetailCommande[]
     */
    public function getCmdDetCmdId(): Collection
    {
        return $this->cmd_det_cmd_id;
    }

    public function addCmdDetCmdId(DetailCommande $cmdDetCmdId): self
    {
        if (!$this->cmd_det_cmd_id->contains($cmdDetCmdId)) {
            $this->cmd_det_cmd_id[] = $cmdDetCmdId;
            $cmdDetCmdId->setDetCmdCmdId($this);
        }

        return $this;
    }

    public function removeCmdDetCmdId(DetailCommande $cmdDetCmdId): self
    {
        if ($this->cmd_det_cmd_id->removeElement($cmdDetCmdId)) {
            // set the owning side to null (unless already changed)
            if ($cmdDetCmdId->getDetCmdCmdId() === $this) {
                $cmdDetCmdId->setDetCmdCmdId(null);
            }
        }

        return $this;
    }

    public function getCmdReference(): ?string
    {
        return $this->cmdReference;
    }

    public function setCmdReference(string $cmdReference): self
    {
        $this->cmdReference = $cmdReference;

        return $this;
    }

    public function getCmdStripIdSession(): ?string
    {
        return $this->cmdStripIdSession;
    }

    public function setCmdStripIdSession(?string $cmdStripIdSession): self
    {
        $this->cmdStripIdSession = $cmdStripIdSession;

        return $this;
    }

}
