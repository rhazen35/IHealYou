<?php

namespace App\Application\Scheduler;

use App\Application\Scheduler\Contracts\AppointmentInterface;

/**
 * Class Scheduler
 * @package App\Application\Scheduler
 */
class Scheduler
{
    /**
     * @var AppointmentInterface
     */
    public $appointment;

    /**
     * Scheduler constructor.
     * @param AppointmentInterface $appointment
     */
    public function __construct(
        AppointmentInterface $appointment
    ) {
        $this->appointment = $appointment;
    }
}