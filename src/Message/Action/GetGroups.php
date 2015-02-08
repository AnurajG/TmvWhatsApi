<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetGroups
 * @package Tmv\WhatsApi\Message\Action
 */
class GetGroups extends AbstractAction implements IdAwareInterface
{
    const TYPE_PARTICIPATING = 'participating';
    const TYPE_OWNING = 'owning';

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $type = 'participating';

    /**
     * @internal
     * @param  string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @internal
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $listNode = new Node();
        $listNode->setName($this->getType());

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'getgroups-',
            "type" => "get",
            "xmlns" => "w:g2",
            "to" => "g.us",
        ]);
        $node->addChild($listNode);

        return $node;
    }

    /**
     * @internal
     * @return bool
     */
    public function isValid()
    {
        $data = [
            $this->getType(),
        ];

        return count(array_filter($data)) == count($data);
    }
}
