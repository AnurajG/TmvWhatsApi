<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetProfilePicture
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class GetProfilePicture extends AbstractAction implements IdAwareInterface
{

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $to;
    /**
     * @var bool
     */
    protected $large = false;

    /**
     * @param      $to
     * @param bool $large
     */
    public function __construct($to, $large = false)
    {
        $this->setTo($to);
        $this->setLarge($large);
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
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLarge()
    {
        return $this->large;
    }

    /**
     * @param boolean $large
     * @return $this
     */
    public function setLarge($large)
    {
        $this->large = $large;
        return $this;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $pictureNode = new Node();
        $pictureNode->setName('picture')
            ->setAttribute('type', $this->isLarge() ? 'image' : 'preview');

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'getpicture-',
            "type" => "get",
            "xmlns" => "w:profile:picture",
            "to" => Identity::createJID($this->getTo())
        ]);
        $node->addChild($pictureNode);

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
