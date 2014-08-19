<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;

/**
 * Class Presence
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class Presence extends AbstractAction
{

    /**
     * @var string
     */
    protected $name;

    function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
