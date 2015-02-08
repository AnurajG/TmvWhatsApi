<?php

namespace Tmv\WhatsApi\Persistence\Adapter;

class FileAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @param array $config
     * @return $this
     */
    public static function factory(array $config)
    {
        if (!isset($config['filepath'])) {
            throw new \InvalidArgumentException(sprintf("Missing configuration key: %s", 'filepath'));
        }
        return new self($config['filepath']);
    }

    public function __construct($filePath)
    {
        $this->setFilePath($filePath);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        if (!$filePath) {
            throw new \InvalidArgumentException("Filepath is not valid");
        }
        $baseDir = dirname($filePath);
        if (!file_exists($baseDir)) {
            throw new \InvalidArgumentException(sprintf("Directory '%s' doesn't exists", $baseDir));
        } elseif (!file_exists($filePath) && !is_writable($baseDir)) {
            throw new \InvalidArgumentException(sprintf("Directory '%s' is not writable", $baseDir));
        } elseif (!file_exists($filePath)) {
            touch($filePath);
        }

        if (!is_writable($filePath)) {
            throw new \InvalidArgumentException(sprintf("File '%s' is not writable", $filePath));
        }
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function set($data)
    {
        file_put_contents($this->getFilePath(), $data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (!file_exists($this->getFilePath())) {
            return null;
        }
        return file_get_contents($this->getFilePath());
    }
}
