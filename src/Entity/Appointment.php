<?php

namespace App\Entity;

use App\Application\Scheduler\OpeningHours;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cancelled;

    /**
     * The duration of an appointment in minutes.
     *
     * @var int $appointmentDuration
     */
    protected $appointmentDuration = 60;

    /**
     * The time between appointments in minutes.
     *
     * @var int $appointmentTimeBetween
     */
    protected $appointmentTimeBetween = 15;

    /**
     * @var string $dayOfTheAppointment
     */
    protected $dayOfTheAppointment;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * Appointment constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->openingHours = new OpeningHours();
    }

    /**
     * @param OpeningHours $openingHours
     * @return bool
     */
    public function isInOpeningHours(): bool
    {
        $dateOfTheAppointment = $this->getDatetime();
        $appointmentDuration = $this->getAppointmentDuration();

        $open = $this->getOpeningHourOfDayOfAppointment();
        $close = $this->getClosingHourOfDayOfAppointment();

        $lastAppointment = clone $close;
        $lastAppointment->sub(DateInterval::createFromDateString($appointmentDuration . ' minutes'));

        $isWithin = $dateOfTheAppointment >= $open && $dateOfTheAppointment <= $lastAppointment;

        return $isWithin;
    }

    public function isOverLapping($appointmentsInDayOfTheAppointment)
    {
        $dateOfTheAppointment = $this->getDatetime();

        dd($appointmentsInDayOfTheAppointment);

        return true;
    }

    /**
     * @return DateTime
     */
    public function getOpeningHourOfDayOfAppointment(): DateTime
    {
        $timeOfOpen = $this->openingHours->{$this->getDayOfTheAppointment()}['start'];

        /** @var DateTime $open */
        $open = clone $this->getDateTime();
        $open->setTime($timeOfOpen[0], $timeOfOpen[1], $timeOfOpen[2]);

        return $open;
    }

    /**
     * @return DateTime
     */
    public function getClosingHourOfDayOfAppointment(): DateTime
    {
        $timeOfClose = $this->openingHours->{$this->getDayOfTheAppointment()}['end'];

        /** @var DateTime $close */
        $close = clone $this->getDateTime();
        $close->setTime($timeOfClose[0], $timeOfClose[1], $timeOfClose[2]);

        return $close;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        $datetime = clone $this->getDatetime();
        return $datetime->format('H:i:s');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return Appointment
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Appointment
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Appointment
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDatetime(): ?DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @param DateTimeInterface $datetime
     * @return Appointment
     */
    public function setDatetime(DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     * @return Appointment
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeInterface $updated_at
     * @return Appointment
     */
    public function setUpdatedAt(DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCancelled(): ?bool
    {
        return $this->cancelled;
    }

    /**
     * @param bool $cancelled
     * @return Appointment
     */
    public function setCancelled(bool $cancelled): self
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    /**
     * @return int
     */
    public function getAppointmentDuration(): int
    {
        return $this->appointmentDuration + $this->getAppointmentTimeBetween();
    }

    /**
     * @param int $appointmentDuration
     * @return Appointment
     */
    public function setAppointmentDuration(int $appointmentDuration): Appointment
    {
        $this->appointmentDuration = $appointmentDuration;

        return $this;
    }

    /**
     * @return int
     */
    public function getAppointmentTimeBetween(): int
    {
        return $this->appointmentTimeBetween;
    }

    /**
     * @param int $appointmentTimeBetween
     * @return Appointment
     */
    public function setAppointmentTimeBetween(int $appointmentTimeBetween): Appointment
    {
        $this->appointmentTimeBetween = $appointmentTimeBetween;

        return $this;
    }

    /**
     * @return string
     */
    public function getDayOfTheAppointment(): string
    {
        return $this->dayOfTheAppointment;
    }

    /**
     * @param string $dayOfTheAppointment
     * @return Appointment
     */
    public function setDayOfTheAppointment(string $dayOfTheAppointment): Appointment
    {
        $this->dayOfTheAppointment = strtolower($dayOfTheAppointment);

        return $this;
    }
}
