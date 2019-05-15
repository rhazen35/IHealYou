<?php

namespace App\Application\Scheduler;

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
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;

        $this->buildMonth();
    }

    /**
     * @throws Exception
     */
    public function buildMonth()
    {
        die(var_dump($this->calendar->getWeekNumbersOfCurrentMonth()));
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
