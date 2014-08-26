<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Tmv\WhatsApi\Client;

abstract class AbstractListener implements ListenerAggregateInterface, ListenerInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param  Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @return \Zend\Stdlib\CallbackHandler[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param  NodeInterface $node
     * @return bool
     */
    protected function isNodeFromMyNumber(NodeInterface $node)
    {
        $currentPhoneNumber = $this->getClient()->getIdentity()->getPhone()->getPhoneNumber();

        return 0 === strncmp($node->getAttribute('from'), $currentPhoneNumber, strlen($currentPhoneNumber));
    }

    /**
     * @param  NodeInterface $node
     * @return bool
     */
    protected function isNodeFromGroup(NodeInterface $node)
    {
        return false !== strpos($node->getAttribute('from'), "-");
    }
}
