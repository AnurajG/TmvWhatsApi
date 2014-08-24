[![Build Status](https://travis-ci.org/thomasvargiu/TmvWhatsApi.png?branch=master)](https://travis-ci.org/thomasvargiu/TmvWhatsApi)
[![Coverage Status](https://coveralls.io/repos/thomasvargiu/TmvWhatsApi/badge.png?branch=master)](https://coveralls.io/r/thomasvargiu/TmvWhatsApi?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thomasvargiu/TmvWhatsApi/badges/quality-score.png?s=c66994bc72499c4771de0e22fb8f257b75685552)](https://scrutinizer-ci.com/g/thomasvargiu/TmvWhatsApi/)

# WhatsAPI

**Status: development**

**Do not use it in production environment!**

## About WhatsAPI

WhatsAPI is a client library to use Whatsapp services.

This is a new project based on the original WhatsAPI:
Please see [the original project](https://github.com/venomous0x/WhatsAPI)

## Why a new project?

The original WhatsAPI library is not compatible with composer, no PSR compatible, and it's very old.
I want to develop this new library in order to make it more usable.
If you want to help, just do it :)

## How to start using this library

### Initializing client ###

```php
use Tmv\WhatsApi\Service\LocalizationService;
use Tmv\WhatsApi\Entity\Phone;
use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Client;

// Initializing client
// Creating a service to retrieve phone info
$localizationService = new LocalizationService();
$localizationService->setCountriesPath(__DIR__ . '/data/countries.csv');

// Creating a phone object...
$phone = new Phone(''); // your phone number with international prefix
// Injecting phone properties
$localizationService->injectPhoneProperties($phone);
// Creating identity
$identity = new Identity();
$identity->setNickname(''); // your name
$identity->setToken('');    // your token
$identity->setPassword(''); // your password
$identity->setPhone($phone);

// Initializing client
$client = new Client($identity);
$client->setChallengeDataFilepath(__DIR__ . '/data/nextChallenge.dat');

// Attaching events...
// ...

// Connecting and login...
$client->connect();
$client->login();

// Actions
// ...

// Polling incoming messages
$time = time();
while (true) {
    $client->pollMessages();
    if (time() - $time >= 10) {
        $time = time();
        // we send a presence message every 10 seconds to avoid server disconnection
        $client->send(new Action\Presence($identity->getNickname()));
    }
}
```

### Sending a message ###

```php
use Tmv\WhatsApi\Message\Action;

$number = ''; // number to send message
// Sending composing notification (simulating typing)
$client->send(new Action\ChatState($number, Action\ChatState::STATE_COMPOSING));
// Sending paused notification (typing end)
$client->send(new Action\ChatState($number, Action\ChatState::STATE_PAUSED));

// Creating message action
$message = new Action\MessageText($identity->getNickname(), $number);
$message->setBody('Hello');

// Sending message...
$client->send($message);
```

### Receiving message ###

```php

use Tmv\WhatsApi\Event\MessageReceivedEvent;
use Tmv\WhatsApi\Message\Received;

// onMessageReceived event
$client->getEventManager()->attach(
    'onMessageReceived',
    function (MessageReceivedEvent $e) {
        $message = $e->getMessage();
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
```

### Debugging ###

It's possible to debug attaching events. It's possible to listen all events attaching to '*' event.

```php
use Zend\EventManager\Event;

// Debug events
$client->getEventManager()->attach(
    'node.received',
    function (Event $e) {
        $node = $e->getParam('node');
        echo sprintf("\n--- Node received:\n%s\n", $node);
    }
);
$client->getEventManager()->attach(
    'node.send.pre',
    function (Event $e) {
        $node = $e->getParam('node');
        echo sprintf("\n--- Sending Node:\n%s\n", $node);
    }
);
```

## Public Events ##

- onMessageReceived (generic event for all messages)
- onMessageTextReceived
- onMessageMediaImageReceived
- onMessageMediaAudioReceived
- onMessageMediaVideoReceived
- onMessageMediaVcardReceived
- onMessageMediaLocationReceived
- onLoginSuccess
- onLoginFailed
- onReceiptServer
- onReceiptClient
- onPresenceReceived
- onGroupParticipantAdded
- onGroupParticipantRemoved