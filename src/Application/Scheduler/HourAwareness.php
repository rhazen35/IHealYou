<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use DateInterval;
use DateTime;

/**
 * Class HourAwareness
 * @package App\Application\Scheduler
 */
class HourAwareness
{
    /**
     * @var Datetime $dateTime
     */
    protected $dateTime;

    /**
     * @var Appointment[]
     */
    protected $appointments;

    /**
     * @var bool $isAware
     */
    protected $isAware;

    /**
     * @var $start
     */
    protected $start;

    /**
     * @var $end
     */
    protected $end;

    /**
     * @var $displayTime
     */
    protected $displayTime;

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return HourAwareness
     */
    public function setDateTime(DateTime $dateTime): HourAwareness
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * @return Appointment[]
     */
    public function getAppointments(): array
    {
        return $this->appointments;
    }

    /**
     * @param Appointment[]
     * @return HourAwareness
     */
    public function setAppointments(array $appointments): HourAwareness
    {
        $this->appointments = $appointments;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAware(): bool
    {
        return $this->isAware;
    }

    /**
     * @param bool $isAware
     * @return HourAwareness
     */
    public function setIsAware(bool $isAware): HourAwareness
    {
        $this->isAware = $isAware;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     * @return HourAwareness
     */
    public function setStart($start): HourAwareness
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     * @return HourAwareness
     */
    public function setEnd($end): HourAwareness
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayTime()
    {
        return $this->displayTime;
    }

    /**
     * @param mixed $displayTime
     * @return HourAwareness
     */
    public function setDisplayTime($displayTime): HourAwareness
    {
        $this->displayTime = $displayTime;

        return $this;
    }

    /**
     * Sense the awareness.
     *
     * @return $this
     */
    public function sense()
    {
        foreach ($this->appointments as $appointment) {

            $start = clone $appointment->getDatetime();

            /** @var DateTime $end */
            $end = clone $start;
            $end->add(DateInterval::createFromDateString($appointment->getAppointmentDuration() . " minutes"));

            if ($start->format("H") === $this->dateTime->format('H')) {

                $this
                    ->setStart($start)
                    ->setEnd($end)
                    ->setDisplayTime($start->format('H:i'))
                    ->setIsAware(true)
                ;
                break;

            } elseif ($start < $this->dateTime && $end > $this->dateTime) {

                $this
                    ->setStart($start)
                    ->setEnd($end)
                    ->setDisplayTime($end->format('H:i'))
                    ->setIsAware(true)
                ;
                break;

            }
        }
        return $this;
    }
}
