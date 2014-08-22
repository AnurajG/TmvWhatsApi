<?php

namespace Tmv\WhatsApi\Message\Received;

use Tmv\WhatsApi\Message\Node\NodeInterface;
use DateTime;
use Tmv\WhatsApi\Message\Received\Media\MediaFactory;

class MessageMediaFactory implements MessageFactoryInterface
{
    /**
     * @param  NodeInterface $node
     * @return MessageMedia
     */
    public function createMessage(NodeInterface $node)
    {
        $message = new MessageMedia();
        $message->setId($node->getAttribute('id'));

        $participant = $node->getAttribute('participant');
        $from = $node->getAttribute('from');

        if ($participant) {
            $message->setFrom($this->getNumberFromJID($participant));
            $message->setGroupId($this->getNumberFromJID($from));
        } else {
            $message->setFrom($this->getNumberFromJID($from));
        }

        $dateTime = new DateTime();
        $dateTime->setTimestamp((int) $node->getAttribute('t'));
        $message->setDateTime($dateTime);
        $message->setNotify($node->getAttribute('notify'));
        $message->setType($node->getAttribute('type'));

        $mediaFactory = $this->createMediaFactory();
        $media = $mediaFactory->createMedia($node->getChild('media'));
        $message->setMedia($media);

        return $message;
    }

    /**
     * @return MediaFactory
     */
    protected function createMediaFactory()
    {
        return new MediaFactory();
    }

    /**
     * @param  string $jid
     * @return string
     */
    protected function getNumberFromJID($jid)
    {
        list($number) = explode('@', $jid, 2);

        return $number;
    }
}
