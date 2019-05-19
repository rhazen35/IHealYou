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
     * BuildCalender constructor.
     * @param Calendar $calendar
     * @param OpeningHours $openingHours
     * @throws Exception
     */
    public function __construct(
        Calendar $calendar,
        OpeningHours $openingHours
    ) {
        $this->calendar = $calendar;
        $this->openingHours = $openingHours;
        $this->appointment = new Appointment();
    }

    /**
     * @param Appointment[] $appointments
     * @return array
     * @throws Exception
     */
    public function buildMonthWithAppointments($appointments)
    {
        $this->appointments = $appointments;
        $monthDaysForCalendar = $this->calendar->getMonthDaysForCalendar();

        $month = [];

        /** @var DateTime $day */
        foreach ($monthDaysForCalendar as $day) {

            $weekNumber = $day->format('W');
            $dayDate = $day->format('Y-m-d');

            $month[$weekNumber][$dayDate] = [];
            $month[$weekNumber][$dayDate]['datetime'] = $day;

            $dayName = strtolower($day->format('l'));

            $timeOfOpen = $this->openingHours->{$dayName}['start'];
            $open = clone $day;
            $open->setTime($timeOfOpen[0], $timeOfOpen[1], $timeOfOpen[2]);

            $timeOfClose = $this->openingHours->{$dayName}['end'];
            $close = clone $day;
            $close->setTime($timeOfClose[0], $timeOfClose[1], $timeOfClose[2]);
            $close->modify('+1 hour');

            $interval = new DateInterval('PT1H');
            $openingHoursRange = new DatePeriod($open, $interval, $close);

            /** @var DateTime $openingHour */
            foreach ($openingHoursRange as $openingHour) {

                $hour = $openingHour->format("H");
                $isFree = true;

                /** @var Appointment $appointment */
                foreach ($this->getAppointmentsInDay($day) as $appointment) {

                    /** @var DateTime $dateTime */
                    $dateTime = $appointment->getDatetime();

                    if ($dateTime->format('H') === $hour) {
                        $isFree = false;

                        $end = clone $dateTime;
                        $end->add(DateInterval::createFromDateString($appointment->getAppointmentDuration() . " minutes"));
                        $month[$weekNumber][$dayDate]['hours'][$hour]['start'] = $dateTime->format('H:i');
                        $month[$weekNumber][$dayDate]['hours'][$hour]['end'] = $end->format('H:i');
                    }
                }

                if ($isFree) {

                    // Get the nearest appointment before the current hour.
                    $appointmentBefore = $this->getAppointmentBeforeOrAfterHour($dayDate, $openingHour, true);
                    // Get the nearest appointment after the current hour.
                    $appointmentAfter = $this->getAppointmentBeforeOrAfterHour($dayDate, $openingHour, false);

                    if ($appointmentBefore && $appointmentAfter) {

                        /** @var DateTime $beforeTime */
                        $beforeTime = clone $appointmentBefore->getDatetime();
                        $beforeTime->add(DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes"));

                        $difference = $beforeTime->diff($appointmentAfter->getDatetime());
                        if ($difference->i < $this->appointment->getAppointmentDuration()) {
                            $isFree = false;
                        }
                    }

                    // Check if the appointment before and the appointment after have a duration in minutes
                    // equal to the appointment duration.
                }

                $month[$weekNumber][$dayDate]['hours'][$hour]['is_free'] = $isFree;
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
     * @param DateTime $openingHour
     * @param bool $before
     * @return Appointment|bool
     */
    public function getAppointmentBeforeOrAfterHour($dayDate, DateTime $openingHour, $before = true)
    {
        $hour = clone $openingHour;

        if ($before) {
            $hour->modify('-1 hour');
        } else {
            $hour->modify('+1 hour');
        }

        $hour = $hour->format('H');

        foreach ($this->appointments as $appointment) {

            $appointmentDate = clone $appointment->getDatetime();

            if ($dayDate === $appointmentDate->format('Y-m-d') && $appointmentDate->format('H') === $hour) {

                return clone $appointment;
            }
        }

        return false;
    }
}
