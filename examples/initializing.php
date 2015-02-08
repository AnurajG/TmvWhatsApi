<?php
use Tmv\WhatsApi\Service\LocalizationService;
use Tmv\WhatsApi\Entity\PhoneFactory;
use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Service\PcntlListener;
use Tmv\WhatsApi\Service\MediaService;
use Tmv\WhatsApi\Options;
use Zend\EventManager\EventInterface;

// Creating a service to retrieve phone info
$localizationService = new LocalizationService(__DIR__ . '/data/countries.csv');

// Creating a phone object...
$phoneFactory = new PhoneFactory($localizationService);
$phone = $phoneFactory->createPhone($number);

// Creating identity
$identity = new Identity($phone);
$identity->setNickname(''); // your name
$identity->setIdentityToken('');    // your token
$identity->setPassword(''); // your password

// Initializing client options
$clientOptions = new Options\ClientOptions();

// Creating MediaService for media messages
// We need to configure it in order to send and receive media files
$mediaServiceOptions = new Options\MediaServiceOptions();
$mediaServiceOptions->setMediaFolder(sys_get_temp_dir());
$mediaServiceOptions->setDefaultImageIconFilepath(__DIR__ . '/data/ImageIcon.jpg');
$mediaServiceOptions->setDefaultVideoIconFilepath(__DIR__ . '/data/VideoIcon.jpg');
$mediaService = new MediaService($mediaServiceOptions);

// Creating the persistence adapter
// We need it to persist the challenge data
// We can use a file adapter with a phone number based unique name
$filepath = sprintf(__DIR__ . '/data/%schallenge.dat', $phone->getPhoneNumber());
$persistenceAdapter = new \Tmv\WhatsApi\Persistence\Adapter\FileAdapter($filepath);

// Or... if you want a custom adapter, you can create a new class, or use a callback adapter
$persistenceAdapter = new \Tmv\WhatsApi\Persistence\Adapter\CallbackAdapter(
    function ($data) {
        // we can save it in db or somewhere else
    },
    function () {
        // we can read it from a db or somewhere else
        return 'data';
    }
);

// Now we can inject depenencies in client options
$clientOptions->setChallengePersistenceAdapter($persistenceAdapter);
$clientOptions->setMediaService($mediaService);

// Initializing client
$client = new Client($identity, $clientOptions);

// Attach PCNTL listener to handle signals (if you have PCNTL extension)
// This allow to kill process softly
$pcntlListener = new PcntlListener();
$client->getEventManager()->attach($pcntlListener);



// Attaching events...
// ...

$client->getEventManager()->attach('onConnected', function(EventInterface $e) {
    /** @var Client $client */
    $client = $e->getTarget();

    // Actions
    // ...
});

// Connect, login and process messages. Automatically send presence
$client->run();