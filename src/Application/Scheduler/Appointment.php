<?php

namespace App\Application\Scheduler;

use App\Application\Scheduler\Contracts\AppointmentInterface;

/**
 * Class Appointment
 * @package App\Application\Scheduler
 */
class Appointment implements AppointmentInterface
{
    /**
     * @var $location
     */
    private $location;

    /**
     * @var ValidateFields
     */
    private $validateFields;

    /**
     * Appointment constructor.
     * @param ValidateFields $validateFields
     */
    public function __construct(ValidateFields $validateFields)
    {
        $this->validateFields = $validateFields;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $validated = $this->validateFields->validate($data);

        if ($validated['type'] === "success") {

            // Store the appointment
        }

        return $validated;

        //DateTime::createFromFormat("Y-m-d\TH:i", $data['datetime'])
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    public function change($appointment)
    {
        // TODO: Implement change() method.
    }

    public function cancel($appointment)
    {
        // TODO: Implement cancel() method.
    }

    /**
     * @return AddressInterface
     */
    public function getLocation(): AddressInterface
    {
        return $this->location;
    }

    /**
     * @param AddressInterface $location
     * @return Appointment
     */
    public function setLocation(AddressInterface $location): Appointment
    {
        $this->location = $location;

        return $this;
    }
}
