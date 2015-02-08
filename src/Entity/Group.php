<?php

namespace Tmv\WhatsApi\Entity;

use DateTime;
use Tmv\WhatsApi\Entity\Group\Participant;

class Group
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $creator;
    /**
     * @var DateTime
     */
    protected $creation;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var Group\Participant[]
     */
    protected $participants = [];

    /**
     * @param  array $data
     * @return Group
     */
    public static function factory(array $data)
    {
        $group = new self();

        $group->setId($data['id']);
        $group->setCreator(Identity::parseJID($data['creator']));
        $creation = new DateTime();
        $creation->setTimestamp((int) $data['creation']);
        $group->setCreation($creation);
        $group->setSubject($data['subject']);

        if (!isset($data['children'])) {
            return $group;
        }

        foreach ($data['children'] as $child) {
            static::addParticipantFromArray($group, $child);
        }

        return $group;
    }

    /**
     * @param Group $group
     * @param array $child
     */
    protected static function addParticipantFromArray(Group $group, array $child)
    {
        if ($child['name'] != 'participant') {
            return;
        }
        $group->addParticipant(
            Identity::parseJID($child['jid']),
            static::getTypeFromParticipantArray($child)
        );
    }

    /**
     * @param array $participant
     * @return string
     */
    protected static function getTypeFromParticipantArray(array $participant)
    {
        return isset($participant['type']) ? $participant['type'] : Participant::TYPE_PARTICIPANT;
    }

    /**
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param string $creator
     * @return $this
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @param  \DateTime $creation
     * @return $this
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreation()
    {
        return $this->creation;
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
     * @param  string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return Group\Participant[]
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param Group\Participant[] $participants
     * @return $this
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * @param Group\Participant $participant
     * @return $this
     */
    public function addParticipant(Group\Participant $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }
}
