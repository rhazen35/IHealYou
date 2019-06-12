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
     * Determines if an appointment can be made at the closing hour.
     *
     * @var bool $appointmentAllowedAtClosingHour
     */
    protected $appointmentAllowedAtClosingHour = true;

    /**
     * @var string $dayOfTheAppointment
     */
    protected $dayOfTheAppointment;

    /**
     * @var OpeningHours
     */
    public $openingHours;

    /**
     * Appointment constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->openingHours = new OpeningHours();
    }

    /**
     * @return bool
     */
    public function isInOpeningHours(): bool
    {
        $open = $this->getOpeningHourOfDayOfAppointment();
        $close = $this->getClosingHourOfDayOfAppointment();

        /** Appointment $lastAppointment */
        $lastAppointment = clone $close;

        if (!$this->isAppointmentAllowedAtClosingHour()) {

            $lastAppointment->sub(DateInterval::createFromDateString( $this->getAppointmentDuration() . ' minutes'));
        }
        return $this->getDatetime() >= $open && $this->getDatetime() <= $lastAppointment;
    }

    /**
     * @param Appointment[] $appointmentsInDayOfTheAppointment
     * @return bool
     */
    public function isOverLapping($appointmentsInDayOfTheAppointment)
    {
        $start = $this->getDatetime();
        $end = $this->getDateTimeWithAppointmentDurationAdded();

        foreach ($appointmentsInDayOfTheAppointment as $appointment) {

            /**
             * @var DateTime $startOfTheAppointment
             * @var DateTime $endOfTheAppointment
             */
            $startOfTheAppointment = clone $appointment->getDatetime();
            $endOfTheAppointment = clone $appointment->getDatetime();
            $endOfTheAppointment->add(
                DateInterval::createFromDateString($this->getAppointmentDuration() . ' minutes')
            );

            if ($startOfTheAppointment >= $start && $endOfTheAppointment < $end) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return DateTime
     */
    public function getDateTimeWithAppointmentDurationSubtracted()
    {
        /** @var DateTime $datTime */
        $datTime = clone $this->getDatetime();
        $datTime->sub(DateInterval::createFromDateString($this->getAppointmentDuration() . " minutes"));

        return $datTime;
    }

    /**
     * @return DateTime
     */
    public function getDateTimeWithAppointmentDurationAdded()
    {
        /** @var DateTime $datTime */
        $datTime = clone $this->getDatetime();
        $datTime->add(DateInterval::createFromDateString($this->getAppointmentDuration() . " minutes"));

        return $datTime;
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
        $this->dayOfTheAppointment = $dayOfTheAppointment;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAppointmentAllowedAtClosingHour(): bool
    {
        return $this->appointmentAllowedAtClosingHour;
    }

    /**
     * @param bool $appointmentAllowedAtClosingHour
     * @return Appointment
     */
    public function setAppointmentAllowedAtClosingHour(bool $appointmentAllowedAtClosingHour): Appointment
    {
        $this->appointmentAllowedAtClosingHour = $appointmentAllowedAtClosingHour;

        return $this;
    }
}
