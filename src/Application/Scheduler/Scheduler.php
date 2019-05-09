<?php

namespace App\Application\Scheduler;

use App\Repository\AppointmentRepository;

/**
 * Class Scheduler
 * @package App\Application\Scheduler
 */
class Scheduler
{
    /**
     * @var AppointmentAcceptable
     */
    private $acceptableAppointment;
    /**
     * @var AppointmentRepository
     */
    private $appointmentRepository;

    /**
     * Scheduler constructor.
     * @param AppointmentAcceptable $acceptableAppointment
     * @param AppointmentRepository $appointmentRepository
     */
    public function __construct(
        AppointmentAcceptable $acceptableAppointment,
        AppointmentRepository $appointmentRepository
    ) {
        $this->acceptableAppointment = $acceptableAppointment;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function newAppointment($data)
    {
        return $this->acceptableAppointment->isAcceptable($data);
    }
}
