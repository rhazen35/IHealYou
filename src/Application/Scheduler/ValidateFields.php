<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;

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
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function validate(Appointment $appointment): array
    {
        $response = [];

        $fullName = $appointment->getFullName() ?? false;
        $email = $appointment->getEmail() ?? false;
        $phone = $appointment->getPhone() ?? false;
        $dateTime = $appointment->getDatetime() ?? false;

        $response['type'] = "failed";

        if (!$fullName) {
            $response['subjects']['fullName'] = "Please fill in your Full Name.";
        }

        if (!$email) {
            $response['subjects']['email'] = "Please fill in your email address.";
        }

        if (!$phone) {
            $response['subjects']['phone'] = "Please fill in your phone number.";
        }

        $response['type'] = $fullName && $email && $phone ? "success" : "failed";

        if (!$dateTime) {

            $response['type'] = "failed";
            $response['subjects']['datetime'] = "Please choose a date and time.";

        } else {

            $appointmentsInDayOfTheAppointment = $this->appointmentRepository->findBetweenDays(
                $appointment->getOpeningHourOfDayOfAppointment(),
                $appointment->getClosingHourOfDayOfAppointment()
            );

            if ($dateTime->format('Y-m-d') < (new DateTime())->format('Y-m-d')) {

                $response['type'] = "failed";
                $response['subjects']['datetime'] = "The appointment occurs in the past and can not be made!";
                return $response;
            }

            $appointmentExists = $this->appointmentRepository->findOneByDateTime($dateTime);
            $isInOpeningHours = $appointment->isInOpeningHours();
            $isOverlapping = $appointment->isOverlapping($appointmentsInDayOfTheAppointment);

            if ($appointmentExists) {

                $response['type'] = "failed";
                $response['subjects']['datetime'] = "The appointment for this date already exists!";
                return $response;
            }

            if (!$isInOpeningHours) {

                $response['type'] = "failed";
                $response['subjects']['datetime'] = "The appointment is not within our opening hours!";
                return $response;
            }

            if ($isOverlapping) {

                $response['type'] = "failed";
                $response['subjects']['datetime'] = "The appointment is already scheduled!";
                return $response;
            }
        }
        return $response;
    }
}
