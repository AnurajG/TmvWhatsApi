<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetGroups
 * @package Tmv\WhatsApi\Message\Action
 */
class GetGroups extends AbstractAction implements IdAwareInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param  string $id
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
     * @return Node
     */
    public function createNode()
    {
        $listNode = new Node();
        $listNode->setName('list');
        $listNode->setAttribute('type', 'participating');

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes(array(
            "id" => null,
            "type" => "get",
            "xmlns" => "w:g",
            "to" => "g.us"
        ));
        $node->addChild($listNode);

        return $node;
    }
}
