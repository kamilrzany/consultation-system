<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\Table(name="reservations")
 */
class Reservation
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
     * @ORM\Column(type="string", length=55)
     */
    private $term;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="reservations")
     */
    private $consultation;

    const PERIOD = 15 * 60;

    public function __construct()
    {
        $this->status = true;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriod(): ?int
    {
        return self::PERIOD;
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

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        return $this;
    }

    public function getFullTerm(): string
    {
        $startDate = new \DateTime($this->getTerm());
        $startTerm = $startDate->getTimestamp();
        $endTerm = $startTerm + self::PERIOD;

        return date('H:i', $startTerm) . ' - ' . date('H:i', $endTerm);
    }
}
