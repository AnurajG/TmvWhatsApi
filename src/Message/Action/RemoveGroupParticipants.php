<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Class RemoveGroupParticipants
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class RemoveGroupParticipants extends ChangeGroupParticipants
{
    /**
     * @param string $groupId
     * @param array  $participants
     */
    function __construct($groupId, array $participants)
    {
        parent::__construct(ChangeGroupParticipants::ACTION_REMOVE, $groupId, $participants);
    }
}
