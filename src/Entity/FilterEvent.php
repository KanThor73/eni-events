<?php

namespace App\Entity;

use App\Repository\FilterEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilterEventRepository::class)]
class FilterEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?Campus $Campus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isOrganizer = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isMember = null;

    #[ORM\Column]
    private ?bool $isNotMember = null;

    #[ORM\Column(nullable: true)]
    private ?bool $passed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampus(): ?object
    {
        return $this->Campus;
    }

    public function setCampus(?object $Campus): static
    {
        $this->Campus = $Campus;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(?string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getBeginDate(): ?\DateTimeImmutable
    {
        return $this->beginDate;
    }

    public function setBeginDate(?\DateTimeImmutable $beginDate): static
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isOrganizer(): ?bool
    {
        return $this->isOrganizer;
    }

    public function setIsOrganizer(?bool $isOrganizer): static
    {
        $this->isOrganizer = $isOrganizer;

        return $this;
    }

    public function isMember(): ?bool
    {
        return $this->isMember;
    }

    public function setIsMember(?bool $isMember): static
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function isNotMember(): ?bool
    {
        return $this->isNotMember;
    }

    public function setIsNotMember(bool $isNotMember): static
    {
        $this->isNotMember = $isNotMember;

        return $this;
    }

    public function isPassed(): ?bool
    {
        return $this->passed;
    }

    public function setPassed(?bool $passed): static
    {
        $this->passed = $passed;

        return $this;
    }
}
