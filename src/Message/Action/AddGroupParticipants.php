<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Class AddGroupParticipants
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class AddGroupParticipants extends ChangeGroupParticipants
{
    /**
     * @param string $groupId
     * @param array  $participants
     */
    function __construct($groupId, array $participants)
    {
        parent::__construct(ChangeGroupParticipants::ACTION_ADD, $groupId, $participants);
    }
}
