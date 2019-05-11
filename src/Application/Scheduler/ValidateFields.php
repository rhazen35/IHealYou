<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
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
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * ValidateFields constructor.
     * @param AppointmentRepository $appointmentRepository
     * @param OpeningHours $openingHours
     */
    public function __construct(
        AppointmentRepository $appointmentRepository,
        OpeningHours $openingHours
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->openingHours = $openingHours;
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

            $appointmentExists = $this->appointmentRepository->findOneByDateTime($dateTime);
            $isAppointmentWithinOpeningHours = $this->isAppointmentWithinOpeningHours($appointment);
            //$appointmentIsOverlapping = $this->dateTimeIsOverlapping($appointment);

            if ($appointmentExists) {
                $dateTime = false;
                $response['subjects']['datetime'] = "The appointment for this date already exists!";
            }
        }

        $response['type'] = in_array(false, [$fullName, $email, $phone, $dateTime]) ? 'failed' : 'success';

        return $response;
    }

    public function isAppointmentWithinOpeningHours(Appointment $appointment)
    {
        $dateTime = $appointment->getDatetime();
        $dayOfTheAppointment = $appointment->getDayOfTheAppointment();
        $openingHours = $this->openingHours->{$dayOfTheAppointment};
        /** @var DateTime $open */
        $open = $openingHours['start'];
        /** @var DateTime $close */
        $close = $openingHours['end'];
        $end = ($close->modify('- ' . $appointment->getAppointmentDuration() . ' minutes'));

        $isWithin = $dateTime >= $open && $dateTime < $end;

        die(var_dump($dayOfTheAppointment));

    }

    public function dateTimeIsOverlapping(Appointment $appointment)
    {
        $dateTime = $appointment->getDatetime();
        $dateTime->format("Y-m-d");
        $dateFrom = clone $dateTime;
        $dateFrom->setTime(00,00,00);
        $dateTo = clone $dateTime;
        $dateTo->setTime(23,59,59);

        $appointments = $this->appointmentRepository->findInDay($dateFrom, $dateTo);

        die(var_dump($appointments));
    }
}
