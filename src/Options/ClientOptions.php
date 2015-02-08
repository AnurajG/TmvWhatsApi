<?php

namespace Tmv\WhatsApi\Options;

use Zend\EventManager\ListenerAggregateInterface;
use Tmv\WhatsApi\Service\MediaService;
use Zend\Stdlib\AbstractOptions;
use Tmv\WhatsApi\Persistence\Adapter\AdapterInterface as PersistenceAdapter;
use Tmv\WhatsApi\Persistence\Adapter\AdapterFactory as PersistenceAdapterFactory;
use Tmv\WhatsApi\Service\ProtocolService;
use Zend\Stdlib\ArrayUtils;

class ClientOptions extends AbstractOptions
{
    /**
     * @var PersistenceAdapter
     */
    protected $challengePersistenceAdapter;
    /**
     * @var MediaService
     */
    protected $mediaService;
    /**
     * @var ProtocolService
     */
    protected $protocolService;
    /**
     * @var array|ListenerAggregateInterface[]
     */
    protected $listeners;
    /**
     * Default Configuration
     * @var array
     */
    protected $defaults = [
        'listeners' => [
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\StreamErrorListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\NotificationListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\ChallengeListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\SuccessListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\FailureListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\MessageListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\ReceiptListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\PresenceListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\ChatStateListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqPingListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqSyncResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqPictureResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqPricingResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqPropsResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqGetGroupsResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IqGetGroupInfoResultListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\IbListener',
            'Tmv\\WhatsApi\\Message\\Node\\Listener\\InjectIdListener',
            'Tmv\\WhatsApi\\Message\\Action\\Listener\\RequestFileUploadListener'
        ],
        'challenge_persistence_adapter' => [
            'class' => 'memory'
        ],
        'media_service' => []
    ];

    /**
     * @param array|\Traversable|null $options
     */
    public function __construct($options = null)
    {
        $this->setFromArray($this->defaults);
        parent::__construct($options);
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
     * @param ProtocolService $protocolService
     * @return $this
     */
    public function setProtocolService(ProtocolService $protocolService)
    {
        $this->protocolService = $protocolService;
        return $this;
    }

    /**
     * @return PersistenceAdapter
     */
    public function getChallengePersistenceAdapter()
    {
        return $this->challengePersistenceAdapter;
    }

    /**
     * @param PersistenceAdapter|array $challengePersistenceAdapter
     * @return $this
     */
    public function setChallengePersistenceAdapter($challengePersistenceAdapter)
    {
        if (is_array($challengePersistenceAdapter)) {
            $challengePersistenceAdapter = PersistenceAdapterFactory::factory($challengePersistenceAdapter);
        }
        if (!$challengePersistenceAdapter instanceof PersistenceAdapter) {
            throw new \InvalidArgumentException("Challenge persistence adapter is not valid");
        }
        $this->challengePersistenceAdapter = $challengePersistenceAdapter;
        return $this;
    }

    /**
     * @return array|ListenerAggregateInterface[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param array|ListenerAggregateInterface[] $listeners
     * @return $this
     */
    public function setListeners(array $listeners)
    {
        $defaults = $this->defaults['listeners'];
        $listeners = array_unique(ArrayUtils::merge($defaults, $listeners));
        $this->listeners = $listeners;
        return $this;
    }

    /**
     * @return MediaService
     */
    public function getMediaService()
    {
        if (!$this->mediaService) {
            $mediaServiceOptions = new MediaServiceOptions();
            $this->mediaService = new MediaService($mediaServiceOptions);
        }
        return $this->mediaService;
    }

    /**
     * @param MediaService|MediaServiceOptions|array|\Traversable $mediaService
     * @return $this
     */
    public function setMediaService($mediaService)
    {
        if ($mediaService instanceof MediaServiceOptions) {
            $mediaService = new MediaService($mediaService);
        }
        if (!$mediaService instanceof MediaService) {
            $mediaServiceOptions = new MediaServiceOptions($mediaService);
            $mediaService = new MediaService($mediaServiceOptions);
        }
        $this->mediaService = $mediaService;
        return $this;
    }
}
