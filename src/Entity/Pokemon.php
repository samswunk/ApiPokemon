<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 * @ApiResource
 */
class Pokemon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("post:read")
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("post:read")
     */
    private $levelevolution;

    /**
     * @ORM\ManyToMany(targetEntity=Attack::class, inversedBy="pokemon")
     */
    private $idattack;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="pokemon")
     * @Groups("post:read")
     */
    private $idtype;

    /**
     * @ORM\ManyToMany(targetEntity=Trainer::class, inversedBy="pokemon",orphanRemoval=true)
     * @Groups("post:read")
     */
    private $idtrainer;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->idattack = new ArrayCollection();
        $this->idtype = new ArrayCollection();
        $this->idtrainer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLevelevolution(): ?int
    {
        return $this->levelevolution;
    }

    public function setLevelevolution(?int $levelevolution): self
    {
        $this->levelevolution = $levelevolution;

        return $this;
    }

    /**
     * @return Collection|Attack[]
     */
    public function getIdattack(): Collection
    {
        return $this->idattack;
    }

    public function addIdattack(Attack $idattack): self
    {
        if (!$this->idattack->contains($idattack)) {
            $this->idattack[] = $idattack;
        }
        return $this;
    }

    public function removeIdattack(Attack $idattack): self
    {
        $this->idattack->removeElement($idattack);

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getIdtype(): Collection
    {
        return $this->idtype;
    }

    public function addIdtype(Type $idtype): self
    {
        if (!$this->idtype->contains($idtype)) {
            $this->idtype[] = $idtype;
        }

        return $this;
    }

    public function removeIdtype(Type $idtype): self
    {
        $this->idtype->removeElement($idtype);

        return $this;
    }

    /**
     * @return Collection|Trainer[]
     */
    public function getIdtrainer(): Collection
    {
        return $this->idtrainer;
    }

    public function addIdtrainer(Trainer $idtrainer): self
    {
        if (!$this->idtrainer->contains($idtrainer)) {
            $this->idtrainer[] = $idtrainer;
        }

        return $this;
    }

    public function setIdtrainer(Trainer $idtrainer): self
    {
        if (!$this->idtrainer->contains($idtrainer)) 
        {
            $this->idtrainer = new ArrayCollection();
            $this->idtrainer[] = $idtrainer;
            // dd("TEST : ",$this->idtrainer->contains($idtrainer),"----------------------------------------------",$idtrainer);
        }

        return $this;
    }

    public function removeIdtrainer(Trainer $idtrainer): self
    {
        $this->idtrainer->removeElement($idtrainer);

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}
