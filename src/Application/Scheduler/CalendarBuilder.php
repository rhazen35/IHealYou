<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

/**
 * Class BuildCalender
 */
class CalendarBuilder
{
    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * @var Appointment[]
     */
    public $appointments;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * @var Appointment
     */
    private $appointment;

    /**
     * @var HourAwareness
     */
    private $hourAwareness;

    /**
     * BuildCalender constructor.
     * @param Calendar $calendar
     * @param OpeningHours $openingHours
     * @param HourAwareness $hourAwareness
     * @throws Exception
     */
    public function __construct(
        Calendar $calendar,
        OpeningHours $openingHours,
        HourAwareness $hourAwareness
    ) {
        $this->calendar = $calendar;
        $this->openingHours = $openingHours;
        $this->appointment = new Appointment();
        $this->hourAwareness = $hourAwareness;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function buildMonthWithAppointments(): array
    {
        $month = [];

        /** @var DateTime $day */
        foreach ($this->calendar->getMonthDaysForCalendar() as $day) {

            $openingHoursRange = new DatePeriod(
                $this->openingHours->openingHourOfDay($day),
                new DateInterval('PT1H'),
                $this->openingHours->closingHourOfDay($day)
            );

            /** @var DateTime $openingHour */
            foreach ($openingHoursRange as $openingHour) {

                // The awareness of the opening hour.
                $awareness = clone $this->hourAwareness;
                $awareness
                    ->setDateTime($openingHour)
                    ->setAppointments($this->getAppointmentsInDay($day))
                    ->setDisplayTime($openingHour->format('H:i'))
                    ->setIsAware(false)
                    ->sense()
                ;

                $week = $day->format('W');
                $date = $day->format('Y-m-d');
                $time = $awareness->getDisplayTime();

                $month[$week][$date]['datetime'] = $day;
                $month[$week][$date]['hours'][$time]['is_open'] = $awareness->isAware() ? false : true;
                $month[$week][$date]['hours'][$time]['start'] = null !== $awareness->getStart()
                    ? $awareness->getStart()->format('H:i')
                    : null
                ;
                $month[$week][$date]['hours'][$time]['end'] = null !== $awareness->getEnd()
                    ? $awareness->getEnd()->format('H:i')
                    : null
                ;
            }
        }
        return $month;
    }

    /**
     * @param DateTime $day
     * @return array|bool
     */
    public function getAppointmentsInDay(DateTime $day)
    {
        $appointmentsInDay = [];

        foreach ($this->appointments as $appointment) {
            if ($appointment->getDatetime()->format('Y-m-d') === $day->format('Y-m-d')) {
                $appointmentsInDay[] = $appointment;
            }
        }
        return $appointmentsInDay ?? false;
    }

    /**
     * @return Appointment[]
     */
    public function getAppointments(): array
    {
        return $this->appointments;
    }

    /**
     * @param Appointment[] $appointments
     * @return CalendarBuilder
     */
    public function setAppointments(array $appointments): CalendarBuilder
    {
        $this->appointments = $appointments;

        return $this;
    }
}
