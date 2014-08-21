<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * Process number/jid and turn it into a JID if necessary
     *
     * @param  string $number
     *                        Number to process
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

    /**
     * @param string$jid
     * @return string
     */
    protected function getNumberFromJID($jid)
    {
        list($number) = explode('@', $jid, 2);

        return $number;
    }
}
