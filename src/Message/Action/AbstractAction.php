<?php

namespace Tmv\WhatsApi\Message\Action;

abstract class AbstractAction implements ActionInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
