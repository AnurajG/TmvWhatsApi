<?php

namespace Tmv\WhatsApi\Entity;

class SyncResult
{
    /**
     * @var int
     */
    protected $index;
    /**
     * @var int
     */
    protected $syncId;
    /**
     * @var array
     */
    protected $existing = [];
    /**
     * @var array
     */
    protected $notExisting = [];

    /**
     * @param int   $index
     * @param int   $syncId
     * @param array $existing
     * @param array $notExisting
     */
    function __construct($index, $syncId, array $existing = [], array $notExisting = [])
    {
        $this->index = $index;
        $this->syncId = $syncId;
        $this->existing = $existing;
        $this->notExisting = $notExisting;
    }


    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return int
     */
    public function getSyncId()
    {
        return $this->syncId;
    }

    /**
     * @param int $syncId
     * @return $this
     */
    public function setSyncId($syncId)
    {
        $this->syncId = $syncId;
        return $this;
    }

    /**
     * @return array
     */
    public function getExisting()
    {
        return $this->existing;
    }

    /**
     * @param array $existing
     * @return $this
     */
    public function setExisting(array $existing)
    {
        $this->existing = $existing;
        return $this;
    }

    /**
     * @return array
     */
    public function getNotExisting()
    {
        return $this->notExisting;
    }

    /**
     * @param array $notExisting
     * @return $this
     */
    public function setNotExisting(array $notExisting)
    {
        $this->notExisting = $notExisting;
        return $this;
    }
}
