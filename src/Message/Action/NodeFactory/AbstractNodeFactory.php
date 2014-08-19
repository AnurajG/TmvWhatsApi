<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Client;

abstract class AbstractNodeFactory
{

    /**
     * Process number/jid and turn it into a JID if necessary
     *
     * @param string $number
     *  Number to process
     * @return string
     */
    protected function getJID($number)
    {
        if (!stristr($number, '@')) {
            //check if group message
            if (stristr($number, '-')) {
                //to group
                $number .= "@" . Client::WHATSAPP_GROUP_SERVER;
            } else {
                //to normal user
                $number .= "@" . Client::WHATSAPP_SERVER;
            }
        }

        return $number;
    }
}
 