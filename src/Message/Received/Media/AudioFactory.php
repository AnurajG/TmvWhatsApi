<?php

namespace Tmv\WhatsApi\Message\Received\Media;

use Tmv\WhatsApi\Message\Node\NodeInterface;

class AudioFactory extends AbstractMediaFactory implements MediaFactoryInterface
{

    /**
     * @param  NodeInterface  $node
     * @return MediaInterface
     */
    public function createMedia(NodeInterface $node)
    {
        $media = new Audio();
        $media->setType($node->getAttribute('type'));
        $media->setIp($node->getAttribute('ip'));
        $media->setData($node->getData());
        $media->setUrl($node->getAttribute('url'));
        $media->setFile($node->getAttribute('file'));
        $media->setMimeType($node->getAttribute('mimetype'));
        $media->setFileHash($node->getAttribute('filehash'));
        $media->setSize($this->convertIntIfValid($node->getAttribute('size')));
        $media->setSeconds($this->convertIntIfValid($node->getAttribute('seconds')));
        $media->setDuration($this->convertIntIfValid($node->getAttribute('duration')));
        $media->setAudioCodec($node->getAttribute('acodec'));
        $media->setAudioBitrate($this->convertIntIfValid($node->getAttribute('abitrate')));
        $media->setAudioSampFreq($this->convertIntIfValid($node->getAttribute('asampfreq')));

        return $media;
    }
}
