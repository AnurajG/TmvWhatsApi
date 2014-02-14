[![Build Status](https://travis-ci.org/thomasvargiu/TmvWhatsApi.png?branch=master)](https://travis-ci.org/thomasvargiu/TmvWhatsApi)
[![Coverage Status](https://coveralls.io/repos/thomasvargiu/TmvWhatsApi/badge.png?branch=master)](https://coveralls.io/r/thomasvargiu/TmvWhatsApi?branch=master)

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

The library is not complete, you can just login and instantiate the first connection.

```php
$number   = ''; // your number
$token    = ''; // token
$nickname = ''; // your name
$password = ''; // your password

$client = new \Tmv\WhatsApi\Client\Client($number, $token, $nickname);
$client->setChallengeDataFilepath(__DIR__ . '/data/nextChallenge.dat');
$client->getEventManager()->attach(
    'login.success',
    function (\Tmv\WhatsApi\Message\Event\SuccessEvent $e) use ($nickname) {
        // Send a message
        $number = '';
        $message = new \Tmv\WhatsApi\Message\Action\MessageText($nickname, $number);
        $message->setBody('Hello');
        $e->getClient()->send($message);
    }
);
$client->connect();
$client->loginWithPassword($password);
while (true) {
    $client->pollMessages();
}
```
