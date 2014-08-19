[![Build Status](https://travis-ci.org/thomasvargiu/TmvWhatsApi.png?branch=master)](https://travis-ci.org/thomasvargiu/TmvWhatsApi)
[![Coverage Status](https://coveralls.io/repos/thomasvargiu/TmvWhatsApi/badge.png?branch=master)](https://coveralls.io/r/thomasvargiu/TmvWhatsApi?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thomasvargiu/TmvWhatsApi/badges/quality-score.png?s=c66994bc72499c4771de0e22fb8f257b75685552)](https://scrutinizer-ci.com/g/thomasvargiu/TmvWhatsApi/)

# WhatsAPI

**Status: development**
*You can use it just to develop on it*


## About WhatsAPI

WhatsAPI is a client library to use Whatsapp services.

This is a new project based on the original WhatsAPI:
Please see [the original project](https://github.com/venomous0x/WhatsAPI)

## Why a new project?

The original WhatsAPI library is not compatible with composer, no PSR compatible, and it's very old.
I want to develop this new library in order to make it more usable.
If you want to help, just do it :)

### The idea is: ###

Just an example:
* The client received a message.
* It's converted in a ```Node``` object, if exists could be a specific ```Node``` object, like ```Success``` node.
* One or more default listeners are attached to the ```node.received``` event. There are also specific event for each tag node. They do all the internal things, like response to system messages.
* Anyone can create a listener to do something on a certain event, like message received, presence changed, etc.

## How to start using this library

The library is not complete, you can just login and instantiate the first connection, sending a text message

```php
$number   = ''; // your number
$token    = ''; // token
$nickname = ''; // your name
$password = ''; // your password

// Creating a service to retrieve phone info
$localizationService = new \Tmv\WhatsApi\Service\LocalizationService();
$localizationService->setCountriesPath(__DIR__ . '/data/countries.csv');

// Creating a phone object...
$phone = new \Tmv\WhatsApi\Entity\Phone($number);
// Injecting phone properties
$phone = $localizationService->dissectPhone($phone);

$identity = new \Tmv\WhatsApi\Entity\Identity();
$identity->setNickname($nickname);
$identity->setToken($token);
$identity->setPassword($password);
$identity->setPhone($phone);

$client = new \Tmv\WhatsApi\Client($identity);
$client->setChallengeDataFilepath(__DIR__ . '/data/nextChallenge.dat');

// Debug events
$client->getEventManager()->attach(
    'node.received',
    function (\Zend\EventManager\Event $e) {
        $node = $e->getParam('node');
        echo sprintf("\n--- Node received:\n%s\n", $node);
    }
);
$client->getEventManager()->attach(
    'node.send.pre',
    function (\Zend\EventManager\Event $e) {
        $node = $e->getParam('node');
        echo sprintf("\n--- Sending Node:\n%s\n", $node);
    }
);

// Connecting...
$client->connect();
$client->login();

$number = ''; // number to send message
$client->send(new \Tmv\WhatsApi\Message\Action\ChatState($number, 'composing'));
$message = new \Tmv\WhatsApi\Message\Action\MessageText($nickname, $number);
$message->setBody('Hello');
$client->send($message);
while (true) {
    $client->pollMessages();
}
```
