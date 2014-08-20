<?php

namespace Tmv\WhatsApi\Protocol;

use Tmv\WhatsApi\Exception\RuntimeException;
use Tmv\WhatsApi\Service\ProtocolService;

class KeyStream
{
    public static $AuthMethod = "WAUTH-2";
    const DROP = 768;
    /**
     * @var RC4
     */
    protected $rc4;
    /**
     * @var string
     */
    protected $macKey;
    protected $seq;
    /** @var ProtocolService */
    protected $protocolService;

    public function __construct($key, $macKey)
    {
        $this->rc4 = new RC4($key, static::DROP);
        $this->macKey = $macKey;
    }

    public static function generateKeys($password, $nonce)
    {
        $array = array(
            "key", //placeholders
            "key",
            "key",
            "key"
        );
        $array2 = array(1, 2, 3, 4);
        $nonce .= '0';
        $count = count($array);
        for ($j = 0; $j < $count; $j++) {
            $nonce[(strlen($nonce) - 1)] = chr($array2[$j]);
            $foo = ProtocolService::pbkdf2("sha1", $password, $nonce, 2, 20, true);
            $array[$j] = $foo;
        }

        return $array;
    }

    public function decodeMessage($buffer, $macOffset, $offset, $length)
    {
        $mac = $this->computeMac($buffer, $offset, $length);
        //validate mac
        for ($i = 0; $i < 4; $i++) {
            $foo = ord($buffer[$macOffset + $i]);
            $bar = ord($mac[$i]);
            if ($foo !== $bar) {
                throw new RuntimeException("MAC mismatch: $foo != $bar");
            }
        }

        return $this->rc4->cipher($buffer, $offset, $length);
    }

    public function encodeMessage($buffer, $macOffset, $offset, $length)
    {
        $data = $this->rc4->cipher($buffer, $offset, $length);
        $mac = $this->computeMac($data, $offset, $length);

        return substr($data, 0, $macOffset) . substr($mac, 0, 4) . substr($data, $macOffset + 4);
    }

    private function computeMac($buffer, $offset, $length)
    {
        $hmac = hash_init("sha1", HASH_HMAC, $this->macKey);
        hash_update($hmac, substr($buffer, $offset, $length));
        $array = chr($this->seq >> 24)
            . chr($this->seq >> 16)
            . chr($this->seq >> 8)
            . chr($this->seq);
        hash_update($hmac, $array);
        $this->seq++;

        return hash_final($hmac, true);
    }

    /**
     * @param  \Tmv\WhatsApi\Service\ProtocolService $protocolService
     * @return $this
     */
    public function setProtocolService($protocolService)
    {
        $this->protocolService = $protocolService;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Service\ProtocolService
     */
    public function getProtocolService()
    {
        return $this->protocolService;
    }
}
