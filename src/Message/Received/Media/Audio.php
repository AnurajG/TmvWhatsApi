<?php

namespace Tmv\WhatsApi\Message\Received\Media;

class Audio extends AbstractMediaFile
{
    /**
     * @var int
     */
    protected $seconds;
    /**
     * @var string
     */
    protected $origin;
    /**
     * @var int
     */
    protected $duration;
    /**
     * @var string
     */
    protected $audioCodec;
    /**
     * @var int
     */
    protected $audioSampFreq;
    /**
     * @var int
     */
    protected $audioBitrate;

    /**
     * @param  int   $audioBitrate
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
     * @param  string $audioCodec
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
     * @param  int   $audioSampFreq
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
     * @param  int   $duration
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
     * @param  string $origin
     * @return $this
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param  int   $seconds
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
}
