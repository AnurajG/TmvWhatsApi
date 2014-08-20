<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Class Receipt
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class Receipt extends AbstractAction
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @param string $to
     * @param string $id
     */
    public function __construct($to = null, $id = null)
    {
        $this->setTo($to);
        $this->setId($id);
    }

    /**
     * @param  string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
}
