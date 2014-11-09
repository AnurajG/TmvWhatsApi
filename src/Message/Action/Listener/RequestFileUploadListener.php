<?php

namespace Tmv\WhatsApi\Message\Action\Listener;

use Tmv\WhatsApi\Message\Action\DirectMediaMessage;
use Tmv\WhatsApi\Message\Action\RequestFileUpload;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Tmv\WhatsApi\Client;

class RequestFileUploadListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $mediaQueue = array();

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('action.send.pre', [$this, 'onSendAction']);
        $this->listeners[] = $events->attach('received.node.iq', array($this, 'onReceivedNode'));
    }

    public function onSendAction(EventInterface $e)
    {
        $action = $e->getParam('action');
        $node = $e->getParam('node');

        if (!$action instanceof RequestFileUpload) {
            return;
        }

        $this->mediaQueue[$action->getId()] = [
            'action' => $action,
            'node' => $node,
        ];
    }

    /**
     * @param EventInterface $e
     */
    public function onReceivedNode(EventInterface $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        /** @var Client $client */
        $client = $e->getTarget();

        if ('result' !== $node->getAttribute('type')) {
            return;
        }
        if (!$node->hasChild('media') && !$node->hasChild('duplicate')) {
            return;
        }

        $id = $node->getAttribute("id");
        $mediaInfo = $this->mediaQueue[$id];
        unset($this->mediaQueue[$id]);

        /** @var RequestFileUpload $action */
        $action = $mediaInfo['action'];

        if ($node->hasChild('duplicate')) {
            $duplicate = $node->getChild('duplicate');
            //file already on whatsapp servers
            $url = $duplicate->getAttribute("url");
            $exploded = explode("/", $url);
            $filename = array_pop($exploded);

            $results = array(
                'type' => $duplicate->getAttribute("type"),
                'url' => $url,
                'name' => $filename,
                'size' => $duplicate->getAttribute("size"),
                'filehash' => $duplicate->getAttribute("filehash"),
            );
        } else {
            $url = $node->getChild("media")->getAttribute("url");
            $results = $client->getMediaService()->uploadMediaFile(
                $action->getMediaFile(),
                $client->getIdentity(),
                $url,
                $action->getTo()
            );

            if (!is_array($results)) {
                throw new \RuntimeException("Upload failed");
            }
        }

        $mediaAction = new DirectMediaMessage();
        $mediaAction->setTo($action->getTo())
            ->setFromName($client->getIdentity()->getNickname())
            ->setUrl($results['url'])
            ->setFile($results['name'])
            ->setSize($results['size'])
            ->setHash($results['filehash'])
            ->setType($results['type']);

        if (!$mediaAction->getIcon()) {
            $icon = null;
            switch ($results['type']) {
                case 'image':
                    // @todo: create icon
                    $icon = $client->getMediaService()->getDefaultImageIcon();
                    break;

                case 'video':
                    // @todo: create icon
                    $icon = $client->getMediaService()->getDefaultVideoIcon();
                    break;
            }
            if ($icon) {
                $mediaAction->setIcon($icon);
            }
        }

        $client->send($mediaAction);
    }

    /**
     * @return array
     */
    public function getMediaQueue()
    {
        return $this->mediaQueue;
    }

    /**
     * @param  array $mediaQueue
     * @return $this
     */
    public function setMediaQueue($mediaQueue)
    {
        $this->mediaQueue = $mediaQueue;

        return $this;
    }
}
