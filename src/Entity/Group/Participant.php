<?php

namespace Tmv\WhatsApi\Entity\Group;

class Participant
{
    const TYPE_PARTICIPANT = 'participant';
    const TYPE_ADMIN = 'admin';

    /**
     * @var string
     */
    protected $number;
    /**
     * @var string
     */
    protected $type = 'participant';

    /**
     * @param string $number
     * @param string $type
     */
    public function __construct($number, $type = 'participant')
    {
        $this->number = $number;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getType() == self::TYPE_ADMIN;
    }
}
