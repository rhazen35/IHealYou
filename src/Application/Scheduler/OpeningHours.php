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
     * @var array $monday
     */
    public $monday;

    /**
     * @var array $tuesday
     */
    public $tuesday;

    /**
     * @var array $wednesday
     */
    public $wednesday;

    /**
     * @var array $thursday
     */
    public $thursday;

    /**
     * @var array $friday
     */
    public $friday;

    /**
     * @var array $saturday
     */
    public $saturday;

    /**
     * @var array $sunday
     */
    public $sunday;

    /**
     * OpeningHours constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $default = [
            'start' => new DateTime("09:00:00"),
            'end' => new DateTime("17:00:00")
        ];

        $this->monday = $default;
        $this->tuesday = $default;
        $this->wednesday = $default;
        $this->thursday = $default;
        $this->friday = $default;
        $this->saturday = $default;
        $this->sunday = $default;
    }
}
