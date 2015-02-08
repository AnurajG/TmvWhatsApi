<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetClientConfig
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class GetClientConfig extends AbstractAction implements IdAwareInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @internal
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @internal
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $config = new Node();
        $config->setName('config');

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'sendconfig-',
            "type" => "get",
            "xmlns" => "urn:xmpp:whatsapp:push",
            "to" => Client::WHATSAPP_SERVER,
        ]);
        $node->addChild($config);

        return $node;
    }

    /**
     * @internal
     * @return bool
     */
    public function isValid()
    {
        return true;
    }
}
