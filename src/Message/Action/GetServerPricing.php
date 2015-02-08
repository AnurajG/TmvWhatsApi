<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Phone;
use Tmv\WhatsApi\Message\Node\Node;

/**
 * Class GetServerPricing
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class GetServerPricing extends AbstractAction implements IdAwareInterface
{

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $language;
    /**
     * @var string
     */
    protected $country;

    /**
     * @param Phone $phone
     * @return GetServerPricing
     */
    public static function fromPhone(Phone $phone)
    {
        return new self($phone->getIso639(), $phone->getIso3166());
    }

    /**
     * @param string $language
     * @param string $country
     */
    public function __construct($language, $country)
    {
        $this->setLanguage($language);
        $this->setCountry($country);
    }

    /**
     * @internal
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @internal
     * @param string $id
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
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @internal
     * @return Node
     */
    public function createNode()
    {
        $pricingNode = new Node();
        $pricingNode->setName('pricing')
            ->setAttribute('lg', $this->getLanguage())
            ->setAttribute('lc', $this->getCountry());

        $node = new Node();
        $node->setName('iq');
        $node->setAttributes([
            "id" => 'get_service_pricing-',
            "type" => "get",
            "xmlns" => "urn:xmpp:whatsapp:account",
            "to" => Client::WHATSAPP_SERVER,
        ]);
        $node->addChild($pricingNode);

        return $node;
    }

    /**
     * @internal
     * @return bool
     */
    public function isValid()
    {
        return true;
    }
}
