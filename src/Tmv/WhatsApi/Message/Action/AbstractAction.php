<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\NodeFactory;

abstract class AbstractAction implements ActionInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

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

    /**
     * @param  NodeFactory $nodeFactory
     * @return $this
     */
    public function setNodeFactory(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;

        return $this;
    }

    /**
     * @return NodeFactory
     */
    public function getNodeFactory()
    {
        if (!$this->nodeFactory) {
            $this->nodeFactory = new NodeFactory();
        }

        return $this->nodeFactory;
    }
}
