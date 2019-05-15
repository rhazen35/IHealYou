<?php

namespace App\Application\Scheduler;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

/**
 * Class Calendar
 * @package App\Application\Scheduler
 */
class Calendar
{
    /**
     * @var DateTime
     */
    protected $today;

    /**
     * Calendar constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->today = new DateTime();
    }

    /**
     * @return DatePeriod
     * @throws Exception
     */
    public function getDaysInCurrentMonth()
    {
        $firstDayOfThisMonth = clone $this->today;
        $firstDayOfThisMonth->modify('first day of this month');

        $lastDayOfThisMonth = clone $this->today;
        $lastDayOfThisMonth->modify('last day of this month');
        $lastDayOfThisMonth->modify('+1 day');

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($firstDayOfThisMonth, $interval, $lastDayOfThisMonth);

        return $dateRange;
    }

    /**
     * @throws Exception
     */
    public function getWeekNumbersOfCurrentMonth()
    {
        $weeks = [];

        /** @var DateTime $day */
        foreach ($this->getDaysInCurrentMonth() as $day) {
            $weekNumber = $day->format("W");
            $weeks[$weekNumber] = $weekNumber;
        }
        return $weeks;
    }
}
