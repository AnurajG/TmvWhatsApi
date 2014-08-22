<?php

namespace Tmv\WhatsApi\Message\Received;

use Tmv\WhatsApi\Message\Node\NodeInterface;
use DateTime;

class MessageTextFactory implements MessageFactoryInterface
{
    /**
     * @param  NodeInterface $node
     * @return MessageText
     */
    public function createMessage(NodeInterface $node)
    {
        $message = new MessageText();
        $message->setBody($node->getChild('body')->getData());

        $participant = $node->getAttribute('participant');
        $from = $node->getAttribute('from');

        if ($participant) {
            $message->setFrom($this->getNumberFromJID($participant));
            $message->setGroupId($this->getNumberFromJID($from));
        } else {
            $message->setFrom($this->getNumberFromJID($from));
        }

        $message->setId($node->getAttribute('id'));
        $dateTime = new DateTime();
        $dateTime->setTimestamp((int) $node->getAttribute('t'));
        $message->setDateTime($dateTime);
        $message->setNotify($node->getAttribute('notify'));
        $message->setType($node->getAttribute('type'));

        return $message;
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
