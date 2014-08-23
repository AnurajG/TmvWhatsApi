<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class ClearDirty
 * Clears the "dirty" status on your account
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ClearDirty extends AbstractAction implements IdAwareInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @param array $categories
     */
    public function __construct(array $categories)
    {
        $this->setCategories($categories);
    }

    /**
     * @param  string $id
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  array $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Node
     */
    public function createNode()
    {
        $clean = new Node();
        $clean->setName('clean')
            ->setAttribute('xmlns', 'urn:xmpp:whatsapp:dirty');

        foreach ($this->getCategories() as $category) {
            $categoryNode = new Node();
            $categoryNode->setName('category')
                ->setAttribute('name', $category);
            $clean->addChild($categoryNode);
        }

        $node = new Node();
        $node->setName('iq')
            ->setAttribute('type', 'set')
            ->setAttribute('to', Client::WHATSAPP_HOST)
            ->setAttribute('id', null)
            ->addChild($clean);

        return $node;
    }
}
