<?php

namespace Tmv\WhatsApi;

use Tmv\WhatsApi\Connection\Adapter\SocketAdapterFactory;
use Tmv\WhatsApi\Connection\Connection;
use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Exception\RuntimeException;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\Listener\ListenerFactory;
use Tmv\WhatsApi\Message\Node\NodeFactory;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Tmv\WhatsApi\Protocol\KeyStream;
use Tmv\WhatsApi\Service\ProtocolService;
use Zend\EventManager\EventManager;

/**
 * Class Client
 * @package Tmv\WhatsApi
 */
class Client
{

    const PORT = 443; // The port of the WhatsApp server.
    const TIMEOUT_SEC = 2; // The timeout for the connection with the WhatsApp servers.
    const TIMEOUT_USEC = 0; //
    const WHATSAPP_CHECK_HOST = 'v.whatsapp.net/v2/exist'; // The check credentials host.
    const WHATSAPP_GROUP_SERVER = 'g.us'; // The Group server hostname
    const WHATSAPP_HOST = 'c.whatsapp.net'; // The hostname of the WhatsApp server.
    const WHATSAPP_REGISTER_HOST = 'v.whatsapp.net/v2/register'; // The register code host.
    const WHATSAPP_REQUEST_HOST = 'v.whatsapp.net/v2/code'; // The request code host.
    const WHATSAPP_SERVER = 's.whatsapp.net'; // The hostname used to login/send messages.
    const WHATSAPP_UPLOAD_HOST = 'https://mms.whatsapp.net/client/iphone/upload.php'; // The upload host.
    const WHATSAPP_DEVICE = 'Android'; // The device name.
    const WHATSAPP_VER = '2.11.134'; // The WhatsApp version.
    const WHATSAPP_USER_AGENT = 'WhatsApp/2.11.134 Android/4.3 Device/GalaxyS3'; // User agent used in request/registration code.

    /**
     * @var bool
     */
    protected $connected = false;
    /**
     * @var EventManager
     */
    protected $eventManager;
    /**
     * @var ProtocolService
     */
    protected $protocolService;
    /**
     * @var string
     */
    protected $challengeData;

    /**
     * @var string
     */
    protected $challengeDataFilepath;

    /**
     * @var NodeFactory
     */
    protected $nodeFactory;
    /**
     * @var Identity
     */
    protected $identity;
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var \Tmv\WhatsApi\Message\Action\NodeFactory
     */
    protected $nodeActionFactory;

    /**
     * Default class constructor.
     *
     * @param Identity $identity
     */
    public function __construct(Identity $identity)
    {

        $listenerFactory = new ListenerFactory();
        $this->getEventManager()->attachAggregate($listenerFactory->factory('StreamError', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Notification', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Challenge', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Success', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Message', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Receipt', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Presence', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('ChatState', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('Iq', $this), 100);
        $this->getEventManager()->attachAggregate($listenerFactory->factory('InjectId', $this), 100);

        $this->setIdentity($identity);
        $this->setConnected(false);
    }

    /**
     * Get the event manager
     *
     * @param EventManager $manager
     *
     * @return EventManager
     */
    public function getEventManager(EventManager $manager = null)
    {
        if (null !== $manager) {
            $this->eventManager = $manager;
        } elseif (null === $this->eventManager) {
            $this->eventManager = new EventManager(__CLASS__);
        }

        return $this->eventManager;
    }

    /**
     * Connect (create a socket) to the WhatsApp network.
     */
    public function connect()
    {
        $this->getConnection()->connect();
    }

    /**
     * Disconnect to the WhatsApp network.
     *
     * @return $this
     */
    public function disconnect()
    {
        $this->getConnection()->disconnect();

        return $this;
    }

    /**
     * Set the connection status with the WhatsApp server
     *
     * @param  boolean $connected
     * @return $this
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;

        return $this;
    }

    /**
     * Get the connection status with the WhatsApp server
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * @param  ProtocolService $protocolService
     * @return $this
     */
    public function setProtocolService($protocolService)
    {
        $this->protocolService = $protocolService;

        return $this;
    }

    /**
     * @return ProtocolService
     */
    public function getProtocolService()
    {
        if (!$this->protocolService) {
            $this->protocolService = new ProtocolService();
        }

        return $this->protocolService;
    }

    /**
     * Get a decoded JSON response from Whatsapp server
     *
     * @param  string $host  The host URL
     * @param  array  $query A associative array of keys and values to send to server.
     * @return object NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit
     */
    protected function getResponse($host, array $query)
    {
        // Build the url.
        $url = $host.'?';
        if (function_exists('http_build_query')) {
            $url .= http_build_query($query);
        } else {
            foreach ($query as $key => $value) {
                $url .= $key.'='.$value.'&';
            }
            $url = rtrim($url, '&');
        }

        // Open connection.
        $ch = curl_init();

        // Configure the connection.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, static::WHATSAPP_USER_AGENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/json'));
        // This makes CURL accept any peer!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Get the response.
        $response = curl_exec($ch);

        // Close the connection.
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Login to the Whatsapp server with your password
     *
     * If you already know your password you can log into the Whatsapp server
     * using this method.
     *
     * @throws RuntimeException
     */
    public function login()
    {
        $challengeData = $this->readChallengeData();
        if (!empty($challengeData)) {
            $this->challengeData = $challengeData;
        }
        $this->doLogin();
    }

    /**
     * Send the nodes to the Whatsapp server to log in.
     */
    protected function doLogin()
    {
        $this->getConnection()->getNodeWriter()->resetKey();
        $this->getConnection()->getNodeReader()->resetKey();
        $resource = static::WHATSAPP_DEVICE.'-'.static::WHATSAPP_VER.'-'.static::PORT;
        $data = $this->getConnection()->getNodeWriter()->startStream(static::WHATSAPP_SERVER, $resource);
        $auth = $this->createAuthNode();

        $this->sendData($data);
        $this->sendNode($this->getNodeFactory()->fromArray(
            array(
                'name' => 'stream:features'
            )
        ));
        $this->sendNode($auth);

        $this->pollMessages();
        $this->pollMessages();
        $this->pollMessages();

        if ($this->challengeData != null) {
            $data = $this->createAuthResponseNode();
            $this->sendNode($data);
            $this->getConnection()->getNodeReader()->setKey($this->getConnection()->getInputKey());
            $this->getConnection()->getNodeWriter()->setKey($this->getConnection()->getOutputKey());
            $this->pollMessages();
        }

        if (!$this->isConnected()) {
            throw new RuntimeException("Login failure");
        }
    }

    /**
     * Send an action to the WhatsApp server.
     *
     * @param  Action\ActionInterface $action
     * @return Action\ActionInterface
     */
    public function send(Action\ActionInterface $action)
    {

        $this->getEventManager()->trigger('action.send.pre', $this, array('action' => $action));

        $nodeFactory = $this->getNodeActionFactory();
        $node = $nodeFactory->createNode($action);

        $node = $this->sendNode($node);
        if ($node->hasAttribute('id')) {
            $action->setId($node->getAttribute('id'));
        }

        $eventParams = array('action' => $action, 'node' => $node);
        $this->getEventManager()->trigger('action.send.post', $this, $eventParams);

        return $action;
    }

    /**
     * Send node to the WhatsApp server.
     * @param  NodeInterface $node
     * @param  bool          $encrypt
     * @return NodeInterface
     */
    public function sendNode(NodeInterface $node, $encrypt = true)
    {
        $argv = compact('node');
        $argv = $this->getEventManager()->prepareArgs($argv);
        $this->getEventManager()->trigger('node.send.pre', $this, $argv);
        $node = $argv['node'];

        $data = $this->getConnection()->getNodeWriter()->write($node, $encrypt);
        $this->sendData($data);

        $this->getEventManager()->trigger('node.send.post', $this, $argv);

        return $node;
    }

    /**
     * Send data to the whatsapp server.
     * @param  string $data
     * @return $this
     */
    protected function sendData($data)
    {
        $this->getConnection()->sendData($data);

        return $this;
    }

    /**
     * Pull from the socket, and place incoming messages in the message queue.
     *
     * @param  bool $autoReceipt
     * @return bool
     */
    public function pollMessages($autoReceipt = true)
    {
        $data = $this->getConnection()->readData();
        if ($data) {
            $this->processInboundData($data, $autoReceipt);

            return true;
        }

        return false;
    }

    /**
     * Process inbound data.
     * @param  bool   $autoReceipt
     * @param  string $data        The data to process.
     * @return $this
     */
    protected function processInboundData($data, $autoReceipt = true)
    {
        $node = $this->getConnection()->getNodeReader()->nextTree($data);
        if ($node) {
            $this->getEventManager()->trigger('node.received',
                $this,
                array('node' => $node, 'autoReceipt' => $autoReceipt)
            );
            $params = array('node' => $node);
            $this->getEventManager()->trigger('received.node.'.$node->getName(), $this, $params);
        }

        return $this;
    }

    /**
     * Add the authentication nodes.
     *
     * @return NodeInterface
     *                       Return itself.
     */
    protected function createAuthNode()
    {
        $authHash = array();
        $authHash["xmlns"] = "urn:ietf:params:xml:ns:xmpp-sasl";
        $authHash["mechanism"] = "WAUTH-2";
        $authHash["user"] = $this->getIdentity()->getPhone()->getPhoneNumber();
        $data = $this->createAuthBlob();

        $node = $this->getNodeFactory()->fromArray(
            array(
                'name' => 'auth',
                'attributes' => $authHash,
                'data' => $data
            )
        );

        return $node;
    }

    /**
     * Create a keystream
     *
     * @param  string    $key
     * @param  string    $macKey
     * @return KeyStream
     */
    protected function createKeyStream($key, $macKey)
    {
        return new KeyStream($key, $macKey);
    }

    protected function createAuthBlob()
    {
        if ($this->challengeData) {
            $key = ProtocolService::pbkdf2('sha1', base64_decode($this->getIdentity()->getPassword()), $this->challengeData, 16, 20, true);
            $this->getConnection()->setInputKey($this->createKeyStream($key[2], $key[3]));
            $this->getConnection()->setOutputKey($this->createKeyStream($key[0], $key[1]));
            $this->getConnection()->getNodeReader()->setKey($this->getConnection()->getInputKey());
            //$this->getConnection()->getNodeWriter()->setKey($this->getConnection()->getOutputKey());
            $phone = $this->getIdentity()->getPhone();
            $array = "\0\0\0\0".
                $phone->getPhoneNumber().
                $this->challengeData.
                time().
                static::WHATSAPP_USER_AGENT.
                " MccMnc/".
                str_pad($phone->getMcc(), 3, "0", STR_PAD_LEFT).
                "001";

            $this->challengeData = null;

            return $this->getConnection()->getOutputKey()->encodeMessage($array, 0, strlen($array), 0);
        }

        return null;
    }

    /**
     * Add the auth response
     *
     * @return NodeInterface
     */
    protected function createAuthResponseNode()
    {
        $resp = $this->authenticate();
        $respHash = array();
        $respHash["xmlns"] = "urn:ietf:params:xml:ns:xmpp-sasl";

        $node = $this->getNodeFactory()->fromArray(
            array(
                'name' => 'response',
                'attributes' => $respHash,
                'data' => $resp
            )
        );

        return $node;
    }

    /**
     * Authenticate with the Whatsapp Server.
     *
     * @return string Returns binary string
     */
    protected function authenticate()
    {
        $keys = KeyStream::generateKeys(base64_decode($this->getIdentity()->getPassword()), $this->challengeData);
        $this->getConnection()->setInputKey($this->createKeyStream($keys[2], $keys[3]));
        $this->getConnection()->setOutputKey($this->createKeyStream($keys[0], $keys[1]));
        $array = "\0\0\0\0".$this->getIdentity()->getPhone()->getPhoneNumber().$this->challengeData;// . time() . static::WHATSAPP_USER_AGENT . " MccMnc/" . str_pad($phone["mcc"], 3, "0", STR_PAD_LEFT) . "001";
        $response = $this->getConnection()->getOutputKey()->encodeMessage($array, 0, 4, strlen($array) - 4);

        return $response;
    }

    /**
     * @param  string $challengeData
     * @return $this
     */
    public function setChallengeData($challengeData)
    {
        $this->challengeData = $challengeData;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallengeData()
    {
        return $this->challengeData;
    }

    /**
     * @param  string $filePath
     * @return $this
     */
    public function setChallengeDataFilepath($filePath)
    {
        $this->challengeDataFilepath = $filePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallengeDataFilepath()
    {
        return $this->challengeDataFilepath;
    }

    /**
     * @param $data
     * @return $this
     */
    public function writeChallengeData($data)
    {
        $this->checkChallengeDataFilePermission();
        $filepath = $this->getChallengeDataFilepath();
        file_put_contents($filepath, $data);

        return $this;
    }

    /**
     * @return string
     */
    public function readChallengeData()
    {
        $this->checkChallengeDataFilePermission();
        $filepath = $this->getChallengeDataFilepath();

        return file_get_contents($filepath);
    }

    /**
     * @return bool
     * @throws \Tmv\WhatsApi\Exception\RuntimeException
     */
    public function checkChallengeDataFilePermission()
    {
        $filePath = $this->getChallengeDataFilepath();
        if (!$filePath) {
            throw new RuntimeException("Filename for challenge data is not setted");
        }
        $baseDir = dirname($filePath);
        if (!file_exists($baseDir)) {
            throw new RuntimeException(sprintf("Directory '%s' doesn't exists", $baseDir));
        } elseif (!file_exists($filePath) && !is_writable($baseDir)) {
            throw new RuntimeException(sprintf("Directory '%s' is not writable", $baseDir));
        } elseif (!file_exists($filePath)) {
            touch($filePath);
        }

        if (!is_writable($filePath)) {
            throw new RuntimeException(sprintf("File '%s' is not writable", $filePath));
        }

        return true;
    }

    /**
     * @param  \Tmv\WhatsApi\Message\Action\NodeFactory $nodeActionFactory
     * @return $this
     */
    public function setNodeActionFactory($nodeActionFactory)
    {
        $this->nodeActionFactory = $nodeActionFactory;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Message\Action\NodeFactory
     */
    public function getNodeActionFactory()
    {
        if (!$this->nodeActionFactory) {
            $this->nodeActionFactory = new Action\NodeFactory();
        }

        return $this->nodeActionFactory;
    }

    /**
     * @param  \Tmv\WhatsApi\Connection\Connection $connection
     * @return $this
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Connection\Connection
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $adapter = SocketAdapterFactory::factory(array(
                'hostname' => static::WHATSAPP_HOST,
                'port' => static::PORT
            ));
            $connection = new Connection($adapter);
            $this->connection = $connection;
        }

        return $this->connection;
    }

    /**
     * @param  \Tmv\WhatsApi\Entity\Identity $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Entity\Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param  \Tmv\WhatsApi\Message\Node\NodeFactory $nodeFactory
     * @return $this
     */
    public function setNodeFactory($nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Message\Node\NodeFactory
     */
    public function getNodeFactory()
    {
        if (!$this->nodeFactory) {
            $this->nodeFactory = new NodeFactory();
        }

        return $this->nodeFactory;
    }
}
