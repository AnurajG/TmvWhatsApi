<?php

namespace Tmv\WhatsApi\Connection;

use Tmv\WhatsApi\Connection\Adapter\AdapterInterface;

use Tmv\WhatsApi\Protocol\BinTree\NodeReader;
use Tmv\WhatsApi\Protocol\BinTree\NodeWriter;

class Connection
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var NodeWriter
     */
    protected $nodeWriter;
    /**
     * @var NodeReader
     */
    protected $nodeReader;

    function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param \Tmv\WhatsApi\Connection\Adapter\AdapterInterface $adapter
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Connection\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param \Tmv\WhatsApi\Protocol\BinTree\NodeReader $nodeReader
     * @return $this
     */
    public function setNodeReader($nodeReader)
    {
        $this->nodeReader = $nodeReader;
        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Protocol\BinTree\NodeReader
     */
    public function getNodeReader()
    {
        if (!$this->nodeReader) {
            $this->nodeReader = new NodeReader();
        }
        return $this->nodeReader;
    }

    /**
     * @param \Tmv\WhatsApi\Protocol\BinTree\NodeWriter $nodeWriter
     * @return $this
     */
    public function setNodeWriter($nodeWriter)
    {
        $this->nodeWriter = $nodeWriter;
        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Protocol\BinTree\NodeWriter
     */
    public function getNodeWriter()
    {
        if (!$this->nodeWriter) {
            $this->nodeWriter = new NodeWriter();
        }
        return $this->nodeWriter;
    }

    /**
     * @return $this
     */
    public function connect()
    {
        $this->getAdapter()->connect();
        return $this;
    }

    /**
     * @return $this
     */
    public function disconnect()
    {
        $this->getAdapter()->disconnect();
        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function sendData($data)
    {
        $this->getAdapter()->sendData($data);
        return $this;
    }

    /**
     * @return string
     */
    public function readData()
    {
        return $this->getAdapter()->readData();
    }
}
 