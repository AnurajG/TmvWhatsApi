<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class CreateGroup
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class CreateGroup extends AbstractAction implements IdAwareInterface
{

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var array
     */
    protected $participants = [];

    /**
     * @param string $subject
     * @param array  $participants
     */
    public function __construct($subject, array $participants)
    {
        $this->subject = $subject;
        $this->participants = $participants;
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return array
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param array $participants
     * @return $this
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {

        $participants = [];
        foreach ($this->getParticipants() as $participant) {
            $participantNode = new Node();
            $participantNode->setName('participant')
                ->setAttribute('jid', Identity::createJID($participant));
            $participants[] = $participantNode;
        }

        $createNode = new Node();
        $createNode->setName('create')
            ->setAttribute('subject', $this->getSubject())
            ->setChildren($participants);

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'creategroup-',
            "type" => "set",
            "xmlns" => "w:g2",
            "to" => 'g.us'
        ]);
        $node->addChild($createNode);

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
