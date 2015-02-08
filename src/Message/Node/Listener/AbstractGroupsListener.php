<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Entity\Group;
use Tmv\WhatsApi\Message\Node\NodeInterface;

abstract class AbstractGroupsListener extends AbstractListener
{

    /**
     * @param  NodeInterface $node
     * @return Group[]
     */
    protected function getGroupsFromNode(NodeInterface $node)
    {
        $groupList = [];
        if ($node->getChild(0) != null) {
            foreach ($node->getChildren() as $child) {
                $groupList[] = Group::factory($child->getAttributes());
            }
        }

        return $groupList;
    }
}
