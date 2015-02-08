<?php

use Tmv\WhatsApi\Options;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Received;
use Zend\EventManager\EventInterface;

include __DIR__ . '/initializing.php';

// Attaching events...
// ...

// onMessageReceived event
$client->getEventManager()->attach(
    'onMessageReceived',
    function (EventInterface $e) {
        // Printing some informations
        /** @var Received\MessageInterface $message */
        $message = $e->getParam('message');

        echo str_repeat('-', 80) . PHP_EOL;
        echo '** MESSAGE RECEIVED **' . PHP_EOL;
        echo sprintf('From: %s', $message->getFrom()) . PHP_EOL;

        if ($message->isFromGroup()) {
            echo sprintf('Group: %s', $message->getGroupId()) . PHP_EOL;
        }
        echo sprintf('Date: %s', $message->getDateTime()->format('Y-m-d H:i:s')) . PHP_EOL;

        if ($message instanceof Received\MessageText) {
            echo PHP_EOL;
            echo sprintf('%s', $message->getBody()) . PHP_EOL;
        } elseif ($message instanceof Received\MessageMedia) {
            echo sprintf('Type: %s', $message->getMedia()->getType()) . PHP_EOL;
        }

        echo str_repeat('-', 80) . PHP_EOL;
    }
);

// Connect, login and process messages. Automatically send presence
$client->run();