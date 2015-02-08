<?php

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Options;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Entity\MediaFileInterface;
use Zend\EventManager\EventInterface;

include __DIR__ . '/initializing.php';

// Attaching events...
// ...

$client->getEventManager()->attach('onConnected', function(EventInterface $e) {
    /** @var Client $client */
    $client = $e->getTarget();

    // Actions
    // ...
    $number = ''; // number to send message
    // Sending composing notification (simulating typing)
    $client->send(new Action\ChatState($number, Action\ChatState::STATE_COMPOSING));
    // Sending paused notification (typing end)
    $client->send(new Action\ChatState($number, Action\ChatState::STATE_PAUSED));

    // Creating text message action
    $message = new Action\MessageText($client->getIdentity()->getNickname(), $number);
    $message->setBody('Hello');

    // OR: creating media (image, video, audio) message (beta)
    $mediaFile = $client->getOptions()
        ->getMediaService()
        ->getMediaFileFactory()
        ->factory('/path/to/image.png', MediaFileInterface::TYPE_IMAGE);
    $message = new Action\MessageMedia();
    $message->setTo($number)
        ->setMediaFile($mediaFile);

    // Sending message...
    $client->send($message);
});

// Connect, login and process messages. Automatically send presence
$client->run();