<?php

namespace Tmv\WhatsApi\Message\Node;

use Tmv\WhatsApi\Exception\InvalidArgumentException;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;

class Message extends AbstractNode
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'message';
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->setAttribute('from', $from);
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->getAttribute('from');
    }
}
