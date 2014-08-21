<?php

namespace Tmv\WhatsApi\Message\Received\Media;

class Video extends AbstractMediaFile
{
    /**
     * @var int
     */
    protected $seconds;
    /**
     * @var string
     */
    protected $encoding;
    /**
     * @var int
     */
    protected $duration;
    /**
     * @var string
     */
    protected $videoCodec;
    /**
     * @var string
     */
    protected $audioCodec;
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var int
     */
    protected $fps;
    /**
     * @var int
     */
    protected $videoBitrate;
    /**
     * @var int
     */
    protected $audioBitrate;
    /**
     * @var int
     */
    protected $audioSampFreq;
    /**
     * @var int
     */
    protected $audioSampFmt;

    /**
     * @param int $seconds
     * @return $this
     */
    public function setSeconds($seconds)
    {
        $this->seconds = $seconds;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param int $audioBitrate
     * @return $this
     */
    public function setAudioBitrate($audioBitrate)
    {
        $this->audioBitrate = $audioBitrate;
        return $this;
    }

    /**
     * @return int
     */
    public function getAudioBitrate()
    {
        return $this->audioBitrate;
    }

    /**
     * @param string $audioCodec
     * @return $this
     */
    public function setAudioCodec($audioCodec)
    {
        $this->audioCodec = $audioCodec;
        return $this;
    }

    /**
     * @return string
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * @param int $audioSampFmt
     * @return $this
     */
    public function setAudioSampFmt($audioSampFmt)
    {
        $this->audioSampFmt = $audioSampFmt;
        return $this;
    }

    /**
     * @return int
     */
    public function getAudioSampFmt()
    {
        return $this->audioSampFmt;
    }

    /**
     * @param int $audioSampFreq
     * @return $this
     */
    public function setAudioSampFreq($audioSampFreq)
    {
        $this->audioSampFreq = $audioSampFreq;
        return $this;
    }

    /**
     * @return int
     */
    public function getAudioSampFreq()
    {
        return $this->audioSampFreq;
    }

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $encoding
     * @return $this
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param int $fps
     * @return $this
     */
    public function setFps($fps)
    {
        $this->fps = $fps;
        return $this;
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $videoBitrate
     * @return $this
     */
    public function setVideoBitrate($videoBitrate)
    {
        $this->videoBitrate = $videoBitrate;
        return $this;
    }

    /**
     * @return int
     */
    public function getVideoBitrate()
    {
        return $this->videoBitrate;
    }

    /**
     * @param string $videoCodec
     * @return $this
     */
    public function setVideoCodec($videoCodec)
    {
        $this->videoCodec = $videoCodec;
        return $this;
    }

    /**
     * @return string
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
 