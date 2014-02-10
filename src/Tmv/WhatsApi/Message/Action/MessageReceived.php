<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Protocol\Node;

/**
 * Class MessageReceived
 * Tell the server we received the message.
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class MessageReceived extends AbstractAction
{

    const RESPONSE_RECEIVED = 'received';
    const RESPONSE_ACK = 'ack';

    /**
     * @var string
     */
    protected $to;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $response;

    public function __construct($to, $id, $response)
    {
        $this->setTo($to);
        $this->setId($id);
        $this->setResponse($response);
    }

    /**
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     */
    public function getNode()
    {
        $node = $this->getNodeFactory()->fromArray(
            array(
                'name'       => 'message',
                'attributes' => array(
                    'to'   => $this->getTo(),
                    'type' => 'chat',
                    'id' => $this->getId(),
                    't' => time()
                ),
                'children'   => array(
                    array(
                        'name' => $this->getResponse(),
                        'attributes' => array(
                            'xmlns' => 'urn:xmpp:receipts'
                        )
                    )
                )
            )
        );

        return $node;
    }

    /**
     * @param  string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param  string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
}
