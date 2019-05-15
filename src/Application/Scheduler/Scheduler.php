<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;

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
     * @var CalendarBuilder
     */
    private $calendarBuilder;

    /**
     * Scheduler constructor.
     * @param AppointmentAcceptable $acceptableAppointment
     * @param AppointmentRepository $appointmentRepository
     * @param CalendarBuilder $calendarBuilder
     */
    public function __construct(
        AppointmentAcceptable $acceptableAppointment,
        AppointmentRepository $appointmentRepository,
        CalendarBuilder $calendarBuilder
    ) {
        $this->acceptableAppointment = $acceptableAppointment;
        $this->appointmentRepository = $appointmentRepository;
        $this->calendarBuilder = $calendarBuilder;
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function newAppointment(Request $request): array
    {
        $data = json_decode($request->getContent(), true)['data'];
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $data['datetime']);
        $appointment = new Appointment();
        $appointment
            ->setFullName($data['fullName'])
            ->setEmail($data['email'])
            ->setPhone($data['phone'])
            ->setDatetime($datetime)
            ->setCreatedAt((new DateTime()))
            ->setCancelled(0)
            ->setDayOfTheAppointment($datetime->format('l'))
        ;

        $isAcceptable = $this->acceptableAppointment->isAcceptable($appointment);

        if ($isAcceptable) {
            $this->appointmentRepository->save($appointment);
        }

        return $this->acceptableAppointment->getValidated();
    }
}
