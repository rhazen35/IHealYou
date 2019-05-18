<?php

namespace App\Application\Scheduler;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class BuildCalender
 */
class CalendarBuilder
{
    /**
     * @var string;
     */
    public $month;

    /**
     * @var string
     */
    public $week;

    /**
     * @var string
     */
    public $day;

    /**
     * @var Calendar
     */
    public $calendar;

    /**
     * BuildCalender constructor.
     * @param Calendar $calendar
     * @throws Exception
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @throws Exception
     */
    public function buildMonthWithAppointments($appointments)
    {
        $monthDaysForCalendar = $this->calendar->getMonthDaysForCalendar();
        $totalDays = $this->calendar->getTotalDaysFromPeriod($monthDaysForCalendar);


        $html = '<div class="month grid-container theme-default">';

        $html .= '<div class="mask top"></div>';
        $html .= '<div class="mask left"></div>';

        $html .= '<div class="content">';
        $html .= '<div class="cal">';

        foreach ($monthDaysForCalendar as $day) {

            $html .= '<div class="day">';
            $html .= $day->format('d');
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="mask right"></div>';
        $html .= '<div class="mask bottom"></div>';

        $html .= '</div>';

        $html .= '<div id="light"></div>';

        return $html;
    }

    public function buildWeek()
    {

    }

    public function buildDay()
    {

    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @param string $month
     * @return CalendarBuilder
     */
    public function setMonth(string $month): CalendarBuilder
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeek(): string
    {
        return $this->week;
    }

    /**
     * @param string $week
     * @return CalendarBuilder
     */
    public function setWeek(string $week): CalendarBuilder
    {
        $this->week = $week;

        return $this;
    }

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return CalendarBuilder
     */
    public function setDay(string $day): CalendarBuilder
    {
        $this->day = $day;

        return $this;
    }
}
