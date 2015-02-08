<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;
use Tmv\WhatsApi\Entity\Identity;

/**
 * Class ChangeNumber
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ChangeNumber extends AbstractAction implements IdAwareInterface
{
    /**
     * @var Identity
     */
    protected $identity;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $number;

    /**
     * @param Identity $identity
     * @param string $number
     */
    public function __construct(Identity $identity, $number)
    {
        $this->setIdentity($identity);
        $this->setNumber($number);
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
     * @return Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param Identity $identity
     * @return $this
     */
    public function setIdentity(Identity $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $usernameNode = new Node();
        $usernameNode->setName('username')
            ->setData($this->getNumber());
        $passwordNode = new Node();
        $passwordNode->setName('password')
            ->setData($this->getIdentity()->getIdentityToken());
        $modifyNode = new Node();
        $modifyNode->setName('modify')
            ->addChild($usernameNode)
            ->addChild($passwordNode);
        $node = new Node();
        $node->setName('iq')
            ->setAttribute('xmlns', 'urn:xmpp:whatsapp:account')
            ->setAttribute('id', 'change_number-')
            ->setAttribute('type', 'get')
            ->setAttribute('to', 'c.us');
        $node->addChild($modifyNode);

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
