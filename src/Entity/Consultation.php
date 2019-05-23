<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=55)
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="consultation", cascade={"persist"})
     * @ORM\OrderBy({"term" = "ASC"})
     */
    private $reservations;

    const PERIOD = 15 * 60;

    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriod(): ?int
    {
        return self::PERIOD;
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

    public function getRoom()
    {
        return $this->room;
    }

    public function setRoom(string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function areDatesValid(\DateTime $startDate, \DateTime $endDate): bool
    {
        $start = date_format($startDate, "d.m.Y H:i");
        $end = date_format($endDate, "d.m.Y H:i");

        return $end > $start ?: false;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setConsultation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            if ($reservation->getConsultation() === $this) {
                $reservation->setConsultation(null);
            }
        }

        return $this;
    }

    public function getOptions()
    {
        $options = [];
        $takenTerms = [];
        foreach ($this->reservations as $reservation) {
            $takenTerms[] = $reservation->getTerm();
        }

        foreach ($this->getAllAvailableOptions() as $key => $klucz) {
            if (!in_array($klucz, $takenTerms)) {
                $options[$key] = $klucz;
            }
        }

        return $options;
    }

    public function getAllAvailableOptions()
    {
        $options = [];
        for ($consultationStart = $this->getStartDate()->getTimestamp(); $consultationStart < $this->getEndDate()->getTimestamp(); $consultationStart += $this->getPeriod()) {
            $consultationEnd = $consultationStart + $this->getPeriod();
            $options[date('H:i', $consultationStart) . ' - ' . date('H:i', $consultationEnd)] = date('H:i', $consultationStart);
        }

        return $options;
    }

    public function canBeListed()
    {
        $options = [];
        for ($consultationStart = $this->getStartDate()->getTimestamp(); $consultationStart < $this->getEndDate()->getTimestamp(); $consultationStart += $this->getPeriod()) {
            $options[] = date('H:i', $consultationStart);
        }

        $takenTerms = [];
        foreach ($this->reservations as $reservation) {
            $takenTerms[] = $reservation->getTerm();
        }

        return count($takenTerms) !== count($options);
    }

    public function getStartDay()
    {
        return date_format($this->startDate, "d.m.Y");
    }

    public function getHoursPeriod()
    {
        return date_format($this->startDate, "H:i") . ' - ' . date_format($this->endDate, "H:i");
    }

    public function getSpots()
    {
        $spots = [];
        for ($consultationStart = $this->getStartDate()->getTimestamp(); $consultationStart < $this->getEndDate()->getTimestamp(); $consultationStart += $this->getPeriod()) {
            $consultationEnd = $consultationStart + $this->getPeriod();
            $spots['available'][] = $consultationEnd;

            $takenSpots = [];
            foreach ($this->reservations as $reservation) {
                $takenSpots[] = $reservation->getTerm();
            }
            $spots['taken'] = $takenSpots;
        }
        return count($spots['available']) - count($spots['taken']) . ' / ' . count($spots['available']);
    }
}
