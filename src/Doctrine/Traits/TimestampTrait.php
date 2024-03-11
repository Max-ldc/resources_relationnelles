<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampTrait
{
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected \DateTimeImmutable $creationDate;

    #[Gedmo\Timestampable(on: 'update')]
    #[Assert\Type(\DateTimeInterface::class)]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?\DateTimeInterface $modificationDate = null;

    public function getCreationDate(): \DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeImmutable $creationDate = null): void
    {
        if (null !== $creationDate) {
            $this->modificationDate = $creationDate;

            return;
        }

        $this->creationDate = new \DateTimeImmutable();
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }
}
