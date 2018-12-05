<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date doit être au bon format")
     * @Assert\GreaterThan("today", message="vous ne pouvez réserver à une date antérieur à ce jour.")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date d'arrivée ne peut être antérieur à la date de départ.")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;


    public function isBookableDates() {
        $isOk=true;
        $notAvailableDays = $this->ad->getNotAvailableDays();
        $bookingDays=$this->getDays();

        // fontion de convertion d'un dateTime en string
        $formatDay = function ($day){
            return $day->format('Y-m-d');
        };

        $days = array_map($formatDay, $bookingDays);
        $notAvailable=array_map($formatDay, $notAvailableDays);

        foreach ($days as $day){
            if (array_search($day, $notAvailable)!==false){
                $isOk= false;
            }
        }

        return $isOk;
    }

    /**
     * Permet de récupérer les journées qui correspondent à ma reservation
     *
     * @return array un tableau d'objet DateTime
     */
    public  function getDays(){
        $resultat=range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24*60*60
        );

        $days = array_map(function($dayTimesStamp){
            return new \DateTime(date('Y-m-d', $dayTimesStamp));
        }, $resultat);

        return $days;
    }

    /**
     * Callback appelé à chaque fois qu'on créé une réservation
     * @ORM\PrePersist
     */
    public function prePersist(){
        if (empty($this->createAt)) {
            $this->createAt=new \DateTime();
        }

        if (empty($this->amount)) {
            $duration = $this->getDuration();
            $amount = $duration*$this->ad->getPrice();

            $this->amount=$amount;
        }
    }

    public function getDuration() {
        $diff=$this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
