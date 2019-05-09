<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use DatePeriod;
use DateTime;

/**
 * Class AppointmentAcceptable
 * @package App\Application\Scheduler
 */
class AppointmentAcceptable
{
    /**
     * The duration of an appointment in minutes.
     *
     * @var int $appointmentDuration
     */
    protected $appointmentDuration = 120;

    /**
     * The time between appointments in minutes.
     *
     * @var int $appointmentTimeBetween
     */
    protected $appointmentTimeBetween = 15;

    /**
     * @var Appointment $appointment
     */
    protected $appointment;

    /**
     * The period in which the appointment date falls.
     *
     * @var DatePeriod $period.
     */
    protected $period;

    /**
     * @var AppointmentRepository
     */
    private $repository;

    /**
     * @var ValidateFields
     */
    private $validateFields;

    /**
     * AppointmentAcceptable constructor.
     * @param AppointmentRepository $repository
     * @param ValidateFields $validateFields
     */
    public function __construct(
        AppointmentRepository $repository,
        ValidateFields $validateFields
    ) {
        $this->repository = $repository;
        $this->validateFields = $validateFields;
    }

    public function isAcceptable($data)
    {
        $validated = $this->validateFields->validate($data);

        if ($validated['type'] === "success") {

            $appointment = new Appointment();
            $appointment
                ->setFullName($data['fullName'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setDatetime(DateTime::createFromFormat("Y-m-d\TH:i", $data['datetime']))
                ->setCreatedAt((new DateTime()))
                ->setCancelled(0)

            ;

            $this->appointment = $appointment;

            // Check if appointment exists
            // or appointment is in range of the appointment duration.
            // Take the appointment tie in between in account.
            // TODO: Cancel appointment (customer)

            $this->repository->save($this->appointment);
        }

        return $validated;
    }

    /**
     * @return int
     */
    public function getAppointmentDuration(): int
    {
        return $this->appointmentDuration;
    }

    /**
     * @param int $appointmentDuration
     * @return AppointmentAcceptable
     */
    public function setAppointmentDuration(int $appointmentDuration): AppointmentAcceptable
    {
        $this->appointmentDuration = $appointmentDuration;

        return $this;
    }

    /**
     * @return DatePeriod
     */
    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    /**
     * @param DatePeriod $period
     */
    public function setPeriod(DatePeriod $period): void
    {
        $this->period = $period;
    }

    /**
     * @return Appointment
     */
    public function getAppointment(): Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment $appointment
     * @return AppointmentAcceptable
     */
    public function setAppointment(Appointment $appointment): AppointmentAcceptable
    {
        $this->appointment = $appointment;

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
     * @return AppointmentAcceptable
     */
    public function setAppointmentTimeBetween(int $appointmentTimeBetween): AppointmentAcceptable
    {
        $this->appointmentTimeBetween = $appointmentTimeBetween;

        return $this;
    }

}
