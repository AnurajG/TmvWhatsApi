<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class ChangeGroupParticipants
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ChangeGroupParticipants extends AbstractAction implements IdAwareInterface
{

    const ACTION_ADD = 'add';
    const ACTION_REMOVE = 'remove';

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $groupId;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var array
     */
    protected $participants = [];

    /**
     * @param string $action
     * @param string $groupId
     * @param array  $participants
     */
    function __construct($action, $groupId, array $participants)
    {
        $this->action = $action;
        $this->groupId = $groupId;
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
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     * @return $this
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
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

        $actionNode = new Node();
        $actionNode->setName($this->getAction())
            ->setChildren($participants);

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'group_participants-',
            "type" => "set",
            "xmlns" => "w:g2",
            "to" => Identity::createJID($this->getGroupId()),
        ]);
        $node->addChild($actionNode);

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
