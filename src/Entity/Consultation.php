<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsultationRepository")
 * @ORM\Table(name="consultations")
 */
class Consultation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime $startDate
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime $endDate
     */
    private $endDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime $createdAt
     */
    private $createdAt;

    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): bool
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->endDate;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

}
