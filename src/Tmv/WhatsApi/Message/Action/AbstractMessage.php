<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client\Client;

/**
 * Abstract Class Message
 *
 * @package Tmv\WhatsApi\Message\Action
 */
abstract class AbstractMessage extends AbstractAction implements MessageInterface
{

    /**
     * @var string
     */
    protected $to;
    /**
     * @var string
     */
    protected $fromName = '';

    /**
     * @param string $fromName
     * @param string $to
     */
    public function __construct($fromName, $to)
    {
        $this->setFromName($fromName);
        $this->setTo($to);
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
                    'to'   => $this->getJID($this->getTo()),
                    'type' => 'chat',
                    'id'   => $this->getId(),
                    't' => time()
                ),
                'children'   => array(
                    array(
                        'name' => 'x',
                        'attributes' => array(
                            'xmlns' => 'jabber:x:event'
                        ),
                        'children' => array(
                            array(
                                'name' => 'server'
                            )
                        )
                    ),
                    array(
                        'name' => 'notify',
                        'attributes' => array(
                            'xmlns' => 'urn:xmpp:whatsapp',
                            'name'  => $this->getFromName()
                        )
                    ),
                    array(
                        'name' => 'request',
                        'attributes' => array(
                            'xmlns' => 'urn:xmpp:receipts'
                        )
                    ),
                    $this->getSubNode()
                )
            )
        );

        return $node;
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

    /**
     * @param string $fromName
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Process number/jid and turn it into a JID if necessary
     *
     * @param string $number
     *  Number to process
     * @return string
     */
    protected function getJID($number)
    {
        if (!stristr($number, '@')) {
            //check if group message
            if (stristr($number, '-')) {
                //to group
                $number .= "@" . Client::WHATSAPP_GROUP_SERVER;
            } else {
                //to normal user
                $number .= "@" . Client::WHATSAPP_SERVER;
            }
        }

        return $number;
    }
}
