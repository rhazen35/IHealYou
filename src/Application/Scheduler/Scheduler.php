<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Presenter\Scheduler\Calendar\CalendarPresenter;
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
     * @var Calendar
     */
    private $calendar;

    /**
     * @var CalendarPresenter
     */
    private $calendarPresenter;

    /**
     * Scheduler constructor.
     * @param AppointmentAcceptable $acceptableAppointment
     * @param AppointmentRepository $appointmentRepository
     * @param Calendar $calendar
     * @param CalendarBuilder $calendarBuilder
     * @param CalendarPresenter $calendarPresenter
     */
    public function __construct(
        AppointmentAcceptable $acceptableAppointment,
        AppointmentRepository $appointmentRepository,
        Calendar $calendar,
        CalendarBuilder $calendarBuilder,
        CalendarPresenter $calendarPresenter
    ) {
        $this->acceptableAppointment = $acceptableAppointment;
        $this->appointmentRepository = $appointmentRepository;
        $this->calendar = $calendar;
        $this->calendarBuilder = $calendarBuilder;
        $this->calendarPresenter = $calendarPresenter;
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

        if ($this->acceptableAppointment->isAcceptable($appointment)) {

            $this->appointmentRepository->save($appointment);
        }
        return $this->acceptableAppointment->getValidated();
    }

    /**
     * @throws Exception
     */
    public function getCalendarMonth()
    {
        $calendarMonthDays = $this->calendar->getMonthDaysForCalendar();

        return $this->calendarPresenter->presentMonthWithAppointments(
            $this->calendarBuilder->setAppointments(
                $this->appointmentRepository->findBetweenDays(
                    $calendarMonthDays->getStartDate(),
                    $calendarMonthDays->getEndDate()
                )
            )->buildMonthWithAppointments()
        );
    }
}
