<?php

namespace App\Entity;

use App\Repository\CollectionTrackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollectionTrackRepository::class)
 */
class CollectionTrack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\Column(type="float")
     */
    private $floorLimit;

    /**
     * @ORM\Column(type="float")
     */
    private $fees;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFloorLimit()
    {
        return $this->floorLimit;
    }

    /**
     * @param mixed $floorLimit
     */
    public function setFloorLimit($floorLimit): void
    {
        $this->floorLimit = $floorLimit;
    }

    /**
     * @return mixed
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * @param mixed $fees
     */
    public function setFees($fees): void
    {
        $this->fees = $fees;
    }
}
