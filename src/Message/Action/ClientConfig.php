<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class ClientConfig
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ClientConfig extends AbstractAction implements IdAwareInterface
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
        $config->setAttributes([
            "platform" => Client::WHATSAPP_DEVICE,
            "version" => Client::WHATSAPP_VER
        ]);

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'config-',
            "type" => "set",
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
