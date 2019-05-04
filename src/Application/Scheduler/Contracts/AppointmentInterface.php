<?php
/**
 * The Appointment Interface manages all appointments.
 */
namespace App\Application\Scheduler\Contracts;

use DateTime;

/**
 * Interface AppointmentInterface
 * @package App\Application\Scheduler\Contracts
 */
interface AppointmentInterface
{
    /**
     * Create a new appointment.
     *
     * The appointment consists of 5 elements:
     *
     * 1.) When is the appointment? $date
     * 2.) Where is the appointment? $location
     * 3.) Which persons want to participate in this appointment? $users
     * 4.) Which persons are the recipients of the participants? $users
     * 5.) In which context will this appointment take place? $context
     *
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
    );

    public function get();

    public function change($appointment);

    public function cancel($appointment);
}