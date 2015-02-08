<?php

namespace Tmv\WhatsApi\Message\Action;

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
    protected $categories = [];

    /**
     * @param string[] $categories
     */
    public function __construct(array $categories)
    {
        $this->setCategories($categories);
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
     * @param  string[] $categories
     * @return $this
     */
    public function setCategories(array $categories)
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
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $node = new Node();
        $node->setName('iq')
            ->setAttribute('type', 'set')
            ->setAttribute('to', "s.whatsapp.net")
            ->setAttribute('id', null)
            ->setAttribute('xmlns', 'urn:xmpp:whatsapp:dirty');

        foreach ($this->getCategories() as $category) {
            $categoryNode = new Node();
            $categoryNode->setName('clean')
                ->setAttribute('type', $category);
            $node->addChild($categoryNode);
        }

        return $node;
    }

    /**
     * @internal
     * @return bool
     */
    public function isValid()
    {
        return count($this->getCategories()) > 0;
    }
}
