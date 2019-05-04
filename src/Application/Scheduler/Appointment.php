<?php

namespace App\Application\Scheduler;

use App\Application\Scheduler\Contracts\AddressInterface;
use App\Application\Scheduler\Contracts\AppointmentContextInterface;
use App\Application\Scheduler\Contracts\AppointmentInterface;
use DateTime;

/**
 * Class Appointment
 * @package App\Application\Scheduler
 */
class Appointment implements AppointmentInterface
{
    /**
     * @var AddressInterface $location
     */
    private $location

    /**
     * @param DateTime $date
     * @param AddressInterface $location
     * @param array UserInterface[] $guests
     * @param array UserInterface[] $recipients
     * @param AppointmentContextInterface $context
     * @return mixed
     */
    public function new(
        DateTime $date,
        AddressInterface $location,
        array $guests,
        array $recipients,
        AppointmentContextInterface $context
    ) {
        // TODO: Implement new() method.
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    public function change($appointment)
    {
        // TODO: Implement change() method.
    }

    public function cancel($appointment)
    {
        // TODO: Implement cancel() method.
    }

    /**
     * @return AddressInterface
     */
    public function getLocation(): AddressInterface
    {
        return $this->location;
    }

    /**
     * @param AddressInterface $location
     * @return Appointment
     */
    public function setLocation(AddressInterface $location): Appointment
    {
        $this->location = $location;

        return $this;
    }
}