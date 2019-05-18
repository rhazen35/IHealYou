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
    public function getDaysInCurrentMonth(): DatePeriod
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
     * @return array
     * @throws Exception
     */
    public function getWeekNumbersOfCurrentMonth(): array
    {
        $weeks = [];

        /** @var DateTime $day */
        foreach ($this->getDaysInCurrentMonth() as $day) {
            $weekNumber = $day->format("W");
            $weeks[$weekNumber] = $weekNumber;
        }
        return $weeks;
    }

    /**
     * @param DatePeriod $period
     * @return int
     * @throws Exception
     */
    public function getTotalDaysFromPeriod(DatePeriod $period): int
    {
        return $period
                ->getEndDate()
                ->diff($period->getStartDate())
                ->days
            ;
    }

    /**
     * @return DatePeriod
     * @throws Exception
     */
    public function getMonthDaysForCalendar(): DatePeriod
    {
        // Get the first monday of this month.
        $firstMonday = new DateTime("first monday of this month");
        $dayOfFirstMonday = (int)$firstMonday->format("d");

        // Get the last monday of this month.
        $lastMonday = new DateTime("last monday of this month");
        $dayOfLastMonday = (int)$lastMonday->format("d");

        // Get the dates in the current month (DatePeriod)
        $daysInCurrentMonth = $this->getDaysInCurrentMonth();

        // Get the start date (first day of the month) and set the day(number).
        $startDate = $daysInCurrentMonth->getStartDate();
        $dayOfStartDay = (int)$startDate->format('d');

        // Get the end date (last day of the month) and set the day(number).
        $endDate = $daysInCurrentMonth->getEndDate();
        $dayOfEndDay = (int)$endDate->format('d');

        // Get the previous monday and set it as the start if the first month day is not a monday.
        if ($dayOfFirstMonday !== $dayOfStartDay) {

            /** @var DateTime $mondayBefore */
            $mondayBefore = clone $startDate;
            $start = $mondayBefore->modify('previous monday');

        } else {
            $start = $startDate;
        }

        // Get the next monday and set it as the end if the last month day is not a monday.
        if ($dayOfEndDay !== $dayOfLastMonday) {

            /** @var DateTime $mondayAfter */
            $mondayAfter = clone $endDate;
            $mondayAfter = $mondayAfter->modify('next monday');
            $end = $mondayAfter;

        } else {
            $end = $endDate;
        }

        // Get the sunday after the last monday and set is as the end.
        $sundayAfter = clone $end;
        $end = $sundayAfter->modify('next sunday');
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);

        return $dateRange;
    }
}
