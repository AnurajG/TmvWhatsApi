<?php

namespace Tmv\WhatsApi\Entity;

class MessageIcon
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param resource|string $resource
     */
    public function __construct($resource)
    {
        if (is_string($resource) && !file_exists($resource)) {
            throw new \InvalidArgumentException("File doesn't exist");
        } elseif (is_string($resource)) {
            $resource = fopen($resource, 'rb');
        }
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException("Parameter should be a resource or file path");
        }

        $this->setResource($resource);
    }

    /**
     * @return resource
     * @codeCoverageIgnore
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param  resource $resource
     * @return $this
     * @codeCoverageIgnore
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getBase64()
    {
        $content = "";
        while (!feof($this->getResource())) {
            $content .= fread($this->getResource(), 1024);
        }

        return base64_encode($content);
    }
}
