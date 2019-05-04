<?php

namespace App\Application\Scheduler\Location;

use App\Application\Scheduler\Location\Contracts\AddressInterface;

/**
 * Class Address
 * @package App\Application\Scheduler\Location
 */
class Address implements AddressInterface
{
    /**
     * @var
     */
    public $street;

    public $streetNumber;

    public $postalCode;

    public $city;

    public $country;

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     * @return Address
     */
    public function setStreet($street): Address
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @param mixed $streetNumber
     * @return Address
     */
    public function setStreetNumber($streetNumber): Address
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode): Address
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Address
     */
    public function setCity($city): Address
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Address
     */
    public function setCountry($country): Address
    {
        $this->country = $country;

        return $this;
    }


}