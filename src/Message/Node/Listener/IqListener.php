<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Group;
use Tmv\WhatsApi\Message\Node\Node;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class IqListener extends AbstractListener
{
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('received.node.iq', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');

        if ($this->isPing($node)) {
            $this->sendPong($node);
        }
        switch ($node->getAttribute('type')) {
            case 'error':
                // todo: handle iq error
                break;
            case 'result':
                // todo: handle iq result

                // group responses
                $this->processGroupsResult($node);
                break;
        }
        if ($node->hasChild('sync')) {
            // todo: handle sync result
        }
    }

    /**
     * @param  NodeInterface $node
     * @param  string        $needle
     * @return bool
     */
    protected function nodeIdContains(NodeInterface $node, $needle)
    {
        return false !== strpos($node->getAttribute("id"), $needle);
    }

    /**
     * @param  NodeInterface $node
     * @return $this
     */
    protected function processGroupsResult(NodeInterface $node)
    {
        switch (true) {
            case ($this->nodeIdContains($node, 'creategroup-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'endgroup-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'getgroupparticipants-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'getgroups-')):
                $this->processGetGroupsResult($node);
                break;

            case ($this->nodeIdContains($node, 'getgroupinfo-')):
                $this->processGetGroupInfoResult($node);
                break;
        }

        return $this;
    }

    /**
     * @param  NodeInterface $node
     * @return $this
     */
    protected function processGetGroupsResult(NodeInterface $node)
    {
        $groupList = $this->getGroupsFromNode($node);
        $this->getClient()->getEventManager()->trigger('onGetGroupsResult',
            $this,
            array(
                'groups' => $groupList
            )
        );

        return $this;
    }

    /**
     * @param  NodeInterface $node
     * @return $this
     */
    protected function processGetGroupInfoResult(NodeInterface $node)
    {
        $groupList = $this->getGroupsFromNode($node);
        $this->getClient()->getEventManager()->trigger('onGetGroupInfoResult',
            $this,
            array(
                'groups' => $groupList
            )
        );

        return $this;
    }

    /**
     * @param  NodeInterface $node
     * @return Group[]
     */
    protected function getGroupsFromNode(NodeInterface $node)
    {
        $groupList = array();
        if ($node->getChild(0) != null) {
            foreach ($node->getChildren() as $child) {
                $groupList[] = Group::factory($child->getAttributes());
            }
        }

        return $groupList;
    }

    /**
     * @param  NodeInterface $node
     * @return bool
     */
    protected function isPing(NodeInterface $node)
    {
        return $node->getAttribute('type') == 'get' && $node->getAttribute('xmlns') == "urn:xmpp:ping";
    }

    /**
     * @param NodeInterface $pingNode
     */
    protected function sendPong(NodeInterface $pingNode)
    {
        $pongNode = new Node();
        $pongNode->setName('iq');
        $pongNode->setAttribute('to', Client::WHATSAPP_SERVER);
        $pongNode->setAttribute('to', $pingNode->getAttribute('id'));
        $pongNode->setAttribute('type', 'result');

        $this->getClient()->sendNode($pongNode);
    }
}
