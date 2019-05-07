<?php

namespace App\Application\Scheduler;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ValidateFields
 * @package App\Application\Scheduler
 */
class ValidateFields
{
    public function __construct()
    {

    }

    /**
     * @param $data
     * @return mixed
     */
    public function validate($data)
    {
        $fullName = empty($data['fullName']) ? false : $data['fullName'];
        $email = empty($data['email']) ? false : $data['email'];
        $phone = empty($data['phone']) ? false : $data['phone'];
        $datetime = empty($data['datetime']) ? false : $data['datetime'];

        if (!$fullName) {$response['subjects']['fullName'] = "Please fill in your Full Name.";}
        if (!$email) {$response['subjects']['email'] = "Please fill in your email address.";}
        if (!$phone) {$response['subjects']['phone'] = "Please fill in your phone number.";}
        if (!$datetime) {$response['subjects']['datetime'] = "Please choose a date and time.";}

        $response['type'] = in_array(false, [$fullName, $email, $phone, $datetime]) ? 'failed' : 'success';

        return $response;
    }
}
