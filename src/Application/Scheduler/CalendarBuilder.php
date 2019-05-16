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

        $this->buildMonth();
    }

    /**
     * @throws Exception
     */
    public function buildMonth()
    {
        $weeks = [];

        $firstMonday = new DateTime("first monday of this month");
        $dayOfMonday = (int)$firstMonday->format("d");

        if ((int)$firstMonday->format("d") !== 1) {
            $mondayBeforeFirstMonday = clone $firstMonday;
            $mondayBeforeFirstMonday->sub(new DateInterval('P7D'));
        }

        $lastMonday = new DateTime("last monday of this month");

        dd($lastMonday);
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
