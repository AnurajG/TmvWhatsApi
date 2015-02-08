<?php

namespace Tmv\WhatsApi\Entity;

/**
 * Class Phone
 *
 * @package Tmv\WhatsApi\Entity
 */
class Phone
{
    /**
     * @var string
     */
    protected $phoneNumber;
    /**
     * @var string
     */
    protected $country;
    /**
     * @var string
     */
    protected $cc;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var string
     */
    protected $mcc;
    /**
     * @var string
     */
    protected $iso3166;
    /**
     * @var string
     */
    protected $iso639;
    /**
     * @var string
     */
    protected $mnc;

    /**
     * @param string $phoneNumber The phone number with international prefix without '+' or '00' prefix
     */
    public function __construct($phoneNumber)
    {
        if (!preg_match('/^[1-9][1-9]\d+$/', $phoneNumber)) {
            throw new \InvalidArgumentException(
                "Invalid number. The number can contain only digits and can't start with '00'"
            );
        }
        $this->setPhoneNumber($phoneNumber);
    }

    /**
     * @param  string $cc
     * @return $this
     */
    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return string
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param  string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the country code
     *
     * @param  string $iso3166
     * @return $this
     */
    public function setIso3166($iso3166)
    {
        $this->iso3166 = $iso3166;

        return $this;
    }

    /**
     * Get the country code
     *
     * @return string
     */
    public function getIso3166()
    {
        return $this->iso3166;
    }

    /**
     * Set the language code
     *
     * @param  string $iso639
     * @return $this
     */
    public function setIso639($iso639)
    {
        $this->iso639 = $iso639;

        return $this;
    }

    /**
     * Get the language code
     *
     * @return string
     */
    public function getIso639()
    {
        return $this->iso639;
    }

    /**
     * @param  string $mcc
     * @return $this
     */
    public function setMcc($mcc)
    {
        $this->mcc = $mcc;

        return $this;
    }

    /**
     * @return string
     */
    public function getMcc()
    {
        return $this->mcc;
    }

    /**
     * Set the phone number without international prefix
     *
     * @param  string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the phone number without international prefix
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the phone number with international prefix
     *
     * @param  string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the phone number with international prefix
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getMnc()
    {
        return $this->mnc;
    }

    /**
     * @param  string $mnc
     * @return $this
     */
    public function setMnc($mnc)
    {
        $this->mnc = $mnc;

        return $this;
    }
}
