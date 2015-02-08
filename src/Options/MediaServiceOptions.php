<?php

namespace Tmv\WhatsApi\Options;

use Zend\Stdlib\AbstractOptions;

class MediaServiceOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $mediaFolder;
    /**
     * @var int
     */
    protected $fileMaxSize = 1048576;
    /**
     * @var string
     */
    protected $defaultImageIconFilepath;
    /**
     * @var string
     */
    protected $defaultVideoIconFilepath;
    /**
     * @var array
     */
    protected $defaults = [
        'media_folder' => null, // Temporary directory where to download media files
        'default_image_icon_filepath' => null, // Default icon for images
        'default_video_icon_filepath' => null, // Default icon for videos
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
     * @return string
     */
    public function getMediaFolder()
    {
        if (!$this->mediaFolder) {
            $this->mediaFolder = sys_get_temp_dir();
        }

        return $this->mediaFolder;
    }

    /**
     * @param  string $mediaFolder
     * @return $this
     */
    public function setMediaFolder($mediaFolder)
    {
        if ($mediaFolder && (!file_exists($mediaFolder) || !is_writable($mediaFolder))) {
            throw new \InvalidArgumentException("Media folder must exists and writable");
        }
        $this->mediaFolder = $mediaFolder;

        return $this;
    }

    /**
     * @return int
     */
    public function getFileMaxSize()
    {
        return $this->fileMaxSize;
    }

    /**
     * @param  int   $fileMaxSize
     * @return $this
     */
    public function setFileMaxSize($fileMaxSize)
    {
        $this->fileMaxSize = $fileMaxSize;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultImageIconFilepath()
    {
        if (!$this->defaultImageIconFilepath) {
            $this->defaultImageIconFilepath = __DIR__.'/../../data/ImageIcon.jpg';
        }

        return $this->defaultImageIconFilepath;
    }

    /**
     * @param  string $defaultImageIconPath
     * @return $this
     */
    public function setDefaultImageIconFilepath($defaultImageIconPath)
    {
        if ($defaultImageIconPath && !file_exists($defaultImageIconPath)) {
            throw new \InvalidArgumentException("Image icon doesn't exist");
        }
        $this->defaultImageIconFilepath = $defaultImageIconPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultVideoIconFilepath()
    {
        if (!$this->defaultVideoIconFilepath) {
            $this->defaultVideoIconFilepath = __DIR__.'/../../data/VideoIcon.jpg';
        }

        return $this->defaultVideoIconFilepath;
    }

    /**
     * @param  string $defaultVideoIconPath
     * @return $this
     */
    public function setDefaultVideoIconFilepath($defaultVideoIconPath)
    {
        if ($defaultVideoIconPath && !file_exists($defaultVideoIconPath)) {
            throw new \InvalidArgumentException("Video icon doesn't exist");
        }
        $this->defaultVideoIconFilepath = $defaultVideoIconPath;

        return $this;
    }
}
