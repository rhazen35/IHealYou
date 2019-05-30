<?php

namespace App\Application\Scheduler;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;

/**
 * Class AppointmentAcceptable
 * @package App\Application\Scheduler
 */
class AppointmentAcceptable
{
    /**
     * @var ValidateFields
     */
    protected $validateFields;

    /**
     * @var array $validated
     */
    protected $validated;

    /**
     * @var AppointmentRepository
     */
    private $appointmentRepository;

    /**
     * AppointmentAcceptable constructor.
     *
     * @param ValidateFields $validateFields
     * @param AppointmentRepository $appointmentRepository
     */
    public function __construct(
        ValidateFields $validateFields,
        AppointmentRepository $appointmentRepository
    ) {
        $this->validateFields = $validateFields;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @param Appointment $appointment
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isAcceptable(Appointment $appointment): bool
    {
        $validated = $this->validateFields->validate($appointment);
        $this->validated = $validated;

        if ($validated['type'] === "success") {

            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getValidated(): array
    {
        return $this->validated;
    }

}
