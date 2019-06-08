<?php

namespace App\Application\Scheduler;

use DateTime;
use Exception;

/**
 * Class OpeningHours
 * @package App\Application\Scheduler
 */
class OpeningHours
{
    /**
     * @var array $Monday
     */
    public $Monday;

    /**
     * @var array $Tuesday
     */
    public $Tuesday;

    /**
     * @var array $Wednesday
     */
    public $Wednesday;

    /**
     * @var array $Thursday
     */
    public $Thursday;

    /**
     * @var array $Friday
     */
    public $Friday;

    /**
     * @var array $Saturday
     */
    public $Saturday;

    /**
     * @var array $Sunday
     */
    public $Sunday;

    /**
     * OpeningHours constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $default = [
            'start' => [9, 00, 00],
            'end' => [17, 00, 00]
        ];

        $this->Monday = $default;
        $this->Tuesday = $default;
        $this->Wednesday = $default;
        $this->Thursday = $default;
        $this->Friday = $default;
        $this->Saturday = $default;
        $this->Sunday = $default;
    }

    public function openingHourOfDay(DateTime $day)
    {
        $timeOfOpen = $this->{$day->format('l')}['start'];
        $open = clone $day;
        $open->setTime($timeOfOpen[0], $timeOfOpen[1], $timeOfOpen[2]);

        return $open;
    }

    public function closingHourOfDay(DateTime $day)
    {
        $timeOfClose = $this->{$day->format('l')}['end'];
        $close = clone $day;
        $close->setTime($timeOfClose[0], $timeOfClose[1], $timeOfClose[2]);
        $close->modify('+1 hour');

        return $close;
    }
}
