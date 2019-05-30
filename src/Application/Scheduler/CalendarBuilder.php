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
        // Set the appointments.
        $this->appointments = $appointments;
        // Get the month days for the calendar.
        $monthDaysForCalendar = $this->calendar->getMonthDaysForCalendar();

        $month = [];

        /**
         * Loop through each day of the month.
         *
         * @var DateTime $day
         */
        foreach ($monthDaysForCalendar as $day) {

            // Set the week number.
            $weekNumber = $day->format('W');

            // Set the date of the day (without the time)
            $dayDate = $day->format('Y-m-d');

            // Set the day name.
            $dayName = strtolower($day->format('l'));

            // Set the day's time of open.
            $timeOfOpen = $this->openingHours->{$dayName}['start'];
            $open = clone $day;
            $open->setTime($timeOfOpen[0], $timeOfOpen[1], $timeOfOpen[2]);

            // Set the day's time of close.
            $timeOfClose = $this->openingHours->{$dayName}['end'];
            $close = clone $day;
            $close->setTime($timeOfClose[0], $timeOfClose[1], $timeOfClose[2]);
            $close->modify('+1 hour');

            // Set the opening hours range with an interval of 1 hour.
            $interval = new DateInterval('PT1H');
            $openingHoursRange = new DatePeriod($open, $interval, $close);

            /**
             * Loop through the opening hours, displaying only these hours.
             *
             * @var DateTime $openingHour
             */
            foreach ($openingHoursRange as $openingHour) {

                // Set the hour of the opening hour.
                $hour = $openingHour->format("H");
                // Set the display time of the opening hour.
                $displayTime = $openingHour->format('H:i');
                // Set is free to true, meaning the hour is open for appointments.
                $isFree = true;

                $start = $end = null;

                /**
                 * Loop through the appointments in the day.
                 *
                 * @var Appointment $appointment
                 */
                foreach ($this->getAppointmentsInDay($day) as $appointment) {

                    /** @var DateTime $dateTime */
                    $dateTime = $appointment->getDatetime();

                    // Add the appointment if the appointment's hour matches the opening hour.
                    if ($dateTime->format('H') === $hour) {

                        // The opening hour is not free anymore.
                        $isFree = false;

                        // Set the end time by adding the appointment's duration.
                        $end = clone $dateTime;
                        $end->add(DateInterval::createFromDateString($appointment->getAppointmentDuration() . " minutes"));

                        $start = $dateTime->format('H:i');
                        $end = $end->format('H:i');

                        break;
                    }
                }

                // Get the appointment before and after the opening hour.
                $appointmentBefore = $this->getAppointmentBeforeOrAfter($dayDate, $openingHour, true);
                $appointmentAfter = $this->getAppointmentBeforeOrAfter($dayDate, $openingHour, false);

                $dateTimeBefore = null;

                if ($appointmentBefore) {

                    /** @var DateTime $dateTimeBefore */
                    $dateTimeBefore = clone $appointmentBefore->getDatetime();
                    $dateTimeBefore->add(DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes"));
                    //$displayTime = $dateTimeBefore->format('H:i');
                }

                if ($appointmentBefore && $appointmentAfter) {

                    $difference = $dateTimeBefore->diff($appointmentAfter->getDatetime());

                    if ($difference->i < $this->appointment->getAppointmentDuration()) {
                        $isFree = false;
                    }
                } elseif (!$appointmentBefore && $appointmentAfter) {

                    $difference = $openingHour->diff($appointmentAfter->getDatetime());

                    if ($difference->i < $this->appointment->getAppointmentDuration()) {
                        $isFree = false;
                    }
                }


                // Opening hour
                $appointmentOpeningHour = clone $this->appointment;
                $appointmentOpeningHour->setDatetime($openingHour);
                $appointmentOpeningHour->setDayOfTheAppointment($dayName);
                $appointmentOpeningHour->openingHours = $this->openingHours;

                if ($hour == $timeOfClose[0]) {

                    $dateTimeOfClose = clone $openingHour;
                    if (!$this->appointment->isAppointmentAllowedAtClosingHour()) {

                        $dateTimeOfClose->sub(DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes"));

                    } else {
                        $dateTimeOfClose->add(DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes"));
                    }

                    foreach ($this->getAppointmentsInDay($day) as $appointment) {

                        /** @var DateTime $dateTimeOfAppointment */
                        $dateTimeOfAppointment = clone $appointment->getDatetime();
                        $dateTimeOfAppointment->add(DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes"));

                        if ($dateTimeOfAppointment > $dateTimeOfClose) {
                            $isFree = false;
                            break;
                        }
                    }
                }

                if (!$appointmentOpeningHour->isInOpeningHours()) {
                    $isFree = false;
                }

                // Add the day date and is free.
                $month[$weekNumber][$dayDate]['datetime'] = $day;
                $month[$weekNumber][$dayDate]['hours'][$displayTime]['is_free'] = $isFree;

                if ($start && $end) {
                    // Add the start and end time to the month array.
                    $month[$weekNumber][$dayDate]['hours'][$displayTime]['start'] = $start;
                    $month[$weekNumber][$dayDate]['hours'][$displayTime]['end'] = $end;
                }
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
     * @param $dayDate
     * @param DateTime $openingHour
     * @param bool $before
     * @return Appointment|bool
     */
    public function getAppointmentBeforeOrAfter($dayDate, DateTime $openingHour, $before = true)
    {
        foreach ($this->appointments as $appointment) {

            /** @var DateTime $appointmentDate */
            $appointmentDate = clone $appointment->getDatetime();

            if ($dayDate === $appointmentDate->format('Y-m-d')) {

                if (
                    $before &&
                    $appointmentDate->add(
                        DateInterval::createFromDateString($this->appointment->getAppointmentDuration() . " minutes")
                    ) >= $openingHour
                ) {
                    return $appointment;
                }

                if (!$before && $appointmentDate <= $openingHour) {
                    return $appointment;
                }
            }
        }
        return false;
    }
}
