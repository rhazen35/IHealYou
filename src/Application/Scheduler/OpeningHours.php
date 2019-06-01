<?php

namespace App\Application\Scheduler;

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
}
