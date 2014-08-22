<?php

namespace Tmv\WhatsApi\Message\Received\Media;

use Tmv\WhatsApi\Message\Node\NodeInterface;

class VideoFactory extends AbstractMediaFactory implements MediaFactoryInterface
{

    /**
     * @param  NodeInterface  $node
     * @return MediaInterface
     */
    public function createMedia(NodeInterface $node)
    {
        $media = new Video();
        $media->setType($node->getAttribute('type'));
        $media->setIp($node->getAttribute('ip'));
        $media->setData($node->getData());
        $media->setUrl($node->getAttribute('url'));
        $media->setFile($node->getAttribute('file'));
        $media->setMimeType($node->getAttribute('mimetype'));
        $media->setFileHash($node->getAttribute('filehash'));
        $media->setWidth($this->convertIntIfValid($node->getAttribute('width')));
        $media->setHeight($this->convertIntIfValid($node->getAttribute('height')));
        $media->setSize($this->convertIntIfValid($node->getAttribute('size')));
        $media->setSeconds($this->convertIntIfValid($node->getAttribute('seconds')));
        $media->setEncoding($node->getAttribute('encoding'));
        $media->setDuration($this->convertIntIfValid($node->getAttribute('duration')));
        $media->setVideoCodec($node->getAttribute('vcodec'));
        $media->setAudioCodec($node->getAttribute('acodec'));
        $media->setFps($this->convertIntIfValid($node->getAttribute('fps')));
        $media->setVideoBitrate($this->convertIntIfValid($node->getAttribute('vbitrate')));
        $media->setAudioBitrate($this->convertIntIfValid($node->getAttribute('abitrate')));
        $media->setAudioSampFreq($this->convertIntIfValid($node->getAttribute('asampfreq')));
        $media->setAudioSampFmt($this->convertIntIfValid($node->getAttribute('asampfmt')));

        return $media;
    }
}
