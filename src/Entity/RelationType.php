<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// RelationType
//$relationTypes = ['Soi', 'Conjoints', 'Famille', 'Professionnel', 'Amis et communautés', 'Inconnus'];
//$relationSubTypes = ['Enfants', 'Parents', 'Fratrie', 'Collègues', 'Collaborateurs',  'Managers'];

#[ORM\Entity]
#[ORM\Table(name: '`relation_type`')]
class RelationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subTypes')]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?self $type = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: self::class)]
    private Collection $subTypes;

    #[ORM\ManyToMany(targetEntity: Resource::class, mappedBy: 'resourceRelationTypes')]
    private Collection $resources;

    public function __construct() {
        $this->subTypes = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?RelationType
    {
        return $this->type;
    }

    public function setType(?RelationType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSubTypes(): Collection
    {
        return $this->subTypes;
    }

    public function setSubTypes(Collection $subTypes): self
    {
        $this->subTypes = $subTypes;

        return $this;
    }

    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function setResources(Collection $resources): self
    {
        $this->resources = $resources;

        return $this;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->addResourceRelationType($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            $resource->removeResourceRelationType($this);
        }

        return $this;
    }

    public function addSubType(RelationType $subType): self
    {
        if (!$this->subTypes->contains($subType)) {
            $this->subTypes[] = $subType;
            $subType->setType($this);
        }

        return $this;
    }

    public function removeSubType(RelationType $subType): self
    {
        if ($this->subTypes->contains($subType)) {
            $this->subTypes->removeElement($subType);
            if ($subType->getType() === $this) {
                $subType->setType(null);
            }
        }

        return $this;
    }
}