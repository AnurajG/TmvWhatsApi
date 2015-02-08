<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetServerProperties
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class GetServerProperties extends AbstractAction implements IdAwareInterface
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
        $props = new Node();
        $props->setName('props');

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'getproperties-',
            "type" => "get",
            "xmlns" => "w",
            "to" => Client::WHATSAPP_SERVER,
        ]);
        $node->addChild($props);

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
