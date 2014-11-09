<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Tmv\WhatsApi\Client;
use Zend\EventManager\ListenerAggregateTrait;

abstract class AbstractListener implements ListenerAggregateInterface, ListenerInterface
{
    use ListenerAggregateTrait;

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
