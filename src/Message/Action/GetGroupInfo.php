<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\Node;

class GetGroupInfo extends AbstractAction implements IdAwareInterface
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $groupId;

    /**
     * @param $groupId
     */
    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

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
     * @param  string $groupId
     * @return $this
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $child = new Node();
        $child->setName('query')
            ->setAttribute('request', 'interactive');

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'getgroupinfo-',
            "type" => "get",
            "xmlns" => "w:g2",
            "to" => Identity::createJID($this->getGroupId()),
        ]);

        $node->addChild($child);

        return $node;
    }

    /**
     * @internal
     * @return bool
     */
    public function isValid()
    {
        $data = [
            $this->getGroupId(),
        ];

        return count(array_filter($data)) == count($data);
    }
}
