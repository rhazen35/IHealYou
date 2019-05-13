<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use DateInterval;
use DateTime;

/**
 * Class ValidateFields
 * @package App\Application\Scheduler
 */
class ValidateFields
{
    /**
     * @var AppointmentRepository
     */
    private $appointmentRepository;

    /**
     * ValidateFields constructor.
     * @param AppointmentRepository $appointmentRepository
     */
    public function __construct(
        AppointmentRepository $appointmentRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validate(Appointment $appointment): array
    {
        $fullName = $appointment->getFullName() ?? false;
        $email = $appointment->getEmail() ?? false;
        $phone = $appointment->getPhone() ?? false;
        $dateTime = $appointment->getDatetime() ?? false;

        if (!$fullName) {$response['subjects']['fullName'] = "Please fill in your Full Name.";}
        if (!$email) {$response['subjects']['email'] = "Please fill in your email address.";}
        if (!$phone) {$response['subjects']['phone'] = "Please fill in your phone number.";}

        if (!$dateTime) {

            $response['subjects']['datetime'] = "Please choose a date and time.";

        } else {

            $appointmentsInDayOfTheAppointment = $this->appointmentRepository->findInDay(
                $appointment->getOpeningHourOfDayOfAppointment(),
                $appointment->getClosingHourOfDayOfAppointment()
            );

            $appointmentExists = $this->appointmentRepository->findOneByDateTime($dateTime);
            $isInOpeningHours = $appointment->isInOpeningHours();
            $isOverlapping = $appointment->isOverlapping($appointmentsInDayOfTheAppointment);

            if ($appointmentExists) {

                $dateTime = false;
                $response['subjects']['datetime'] = "The appointment for this date already exists!";

            } elseif (!$isInOpeningHours) {

                $dateTime = false;
                $response['subjects']['datetime'] = "The appointment is not within our opening hours!";

            } elseif ($isOverlapping) {

                $dateTime = false;
                $response['subjects']['datetime'] = "The appointment is already scheduled!";
            }
        }

        $response['type'] = in_array(false, [$fullName, $email, $phone, $dateTime]) ? 'failed' : 'success';

        return $response;
    }
}
