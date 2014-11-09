<?php

namespace Tmv\WhatsApi\Service;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Entity\MediaFile;
use Tmv\WhatsApi\Entity\RemoteMediaFile;
use Tmv\WhatsApi\Entity\MessageIcon;
use Tmv\WhatsApi\Entity\MediaFileFactory;
use Tmv\WhatsApi\Options\MediaService as ServiceOptions;

class MediaService
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
     * @var MessageIcon
     */
    protected $defaultImageIcon;
    /**
     * @var MessageIcon
     */
    protected $defaultVideoIcon;
    /**
     * @var MediaFileFactory
     */
    protected $mediaFileFactory;
    /**
     * @var ServiceOptions
     */
    protected $options;

    public function __construct(ServiceOptions $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * @return ServiceOptions
     */
    public function getOptions()
    {
        if (null === $this->options) {
            $this->options = new ServiceOptions();
        }

        return $this->options;
    }

    /**
     * @param  ServiceOptions $options
     * @return $this
     */
    public function setOptions(ServiceOptions $options)
    {
        $this->options = $options;
        if ($options->getDefaultImageIconFilepath()) {
            $this->setDefaultImageIcon(new MessageIcon($options->getDefaultImageIconFilepath()));
        }
        if ($options->getDefaultVideoIconFilepath()) {
            $this->setDefaultVideoIcon(new MessageIcon($options->getDefaultVideoIconFilepath()));
        }

        return $this;
    }

    /**
     * @return MessageIcon
     */
    public function getDefaultImageIcon()
    {
        return $this->defaultImageIcon;
    }

    /**
     * @param  MessageIcon $defaultImageIcon
     * @return $this
     */
    public function setDefaultImageIcon(MessageIcon $defaultImageIcon)
    {
        $this->defaultImageIcon = $defaultImageIcon;

        return $this;
    }

    /**
     * @return MessageIcon
     */
    public function getDefaultVideoIcon()
    {
        return $this->defaultVideoIcon;
    }

    /**
     * @param  MessageIcon $defaultVideoIcon
     * @return $this
     */
    public function setDefaultVideoIcon(MessageIcon $defaultVideoIcon)
    {
        $this->defaultVideoIcon = $defaultVideoIcon;

        return $this;
    }

    /**
     * @return MediaFileFactory
     */
    public function getMediaFileFactory()
    {
        if (!$this->mediaFileFactory) {
            $this->mediaFileFactory = new MediaFileFactory($this);
        }

        return $this->mediaFileFactory;
    }

    /**
     * @param  MediaFileFactory $mediaFileFactory
     * @return $this
     */
    public function setMediaFileFactory(MediaFileFactory $mediaFileFactory)
    {
        $this->mediaFileFactory = $mediaFileFactory;

        return $this;
    }

    /**
     * Download remote file
     *
     * @param  RemoteMediaFile $mediaFile
     * @return RemoteMediaFile
     */
    public function downloadRemoteMediaFile(RemoteMediaFile $mediaFile)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $mediaFile->getUrl());
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, true);

        if (curl_exec($curl) === false) {
            curl_close($curl);
            throw new \RuntimeException("An error occurred downloading the file");
        }

        $info = curl_getinfo($curl);
        $mediaFile->setSize($info['download_content_length']);
        $mediaFile->setMimeType($info['content_type']);
        $mediaFile->setExtension(pathinfo(parse_url($mediaFile->getUrl(), PHP_URL_PATH), PATHINFO_EXTENSION));

        if ($mediaFile->getSize() > $this->getOptions()->getFileMaxSize()) {
            curl_close($curl);
            throw new \RuntimeException("File is too big");
        }

        $filepath = tempnam($this->getOptions()->getMediaFolder(), 'WHA');
        $fp = fopen($filepath, 'w');
        if (!$fp) {
            curl_close($curl);
            throw new \RuntimeException("Unable to save the file. Check permissions");
        }
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_BUFFERSIZE, 1024);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_exec($curl);
        fclose($fp);
        curl_close($curl);

        $mediaFile->setFilepath($filepath);

        return $mediaFile;
    }

    public function uploadMediaFile(MediaFile $mediaFile, Identity $identity, $uploadUrl, $to)
    {
        return $this->sendMediaFile(
            $mediaFile,
            $uploadUrl,
            Identity::createJID($to),
            $identity->getPhone()->getPhoneNumber()
        );
    }

    /**
     * @param  MediaFile  $mediafile
     * @param  string     $uploadUrl
     * @param  string     $to
     * @param  string     $from
     * @return bool|array
     */
    protected function sendMediaFile(MediaFile $mediafile, $uploadUrl, $to, $from)
    {
        $host = parse_url($uploadUrl, PHP_URL_HOST);

        //filename to md5 digest
        $cryptoname = md5($mediafile->getFilepath()).".".$mediafile->getExtension();
        $boundary = "zzXXzzYYzzXXzzQQ";
        $contentlength = 0;

        if (is_array($to)) {
            $to = implode(',', $to);
        }

        $hBAOS = "--".$boundary."\r\n";
        $hBAOS .= "Content-Disposition: form-data; name=\"to\"\r\n\r\n";
        $hBAOS .= $to."\r\n";
        $hBAOS .= "--".$boundary."\r\n";
        $hBAOS .= "Content-Disposition: form-data; name=\"from\"\r\n\r\n";
        $hBAOS .= $from."\r\n";
        $hBAOS .= "--".$boundary."\r\n";
        $hBAOS .= "Content-Disposition: form-data; name=\"file\"; filename=\"".$cryptoname."\"\r\n";
        $hBAOS .= "Content-Type: ".$mediafile->getMimeType()."\r\n\r\n";

        $fBAOS = "\r\n--".$boundary."--\r\n";

        $contentlength += strlen($hBAOS);
        $contentlength += strlen($fBAOS);
        $contentlength += $mediafile->getSize();

        $post = "POST ".$uploadUrl."\r\n";
        $post .= "Content-Type: multipart/form-data; boundary=".$boundary."\r\n";
        $post .= "Host: ".$host."\r\n";
        $post .= "User-Agent: ".Client::WHATSAPP_USER_AGENT."\r\n";
        $post .= "Content-Length: ".$contentlength."\r\n\r\n";

        return $this->sendData($mediafile, $host, $post, $hBAOS, $fBAOS);
    }

    /**
     * @param  MediaFile  $mediaFile
     * @param  string     $host
     * @param  string     $post
     * @param  string     $head
     * @param  string     $tail
     * @return bool|array
     */
    protected function sendData(MediaFile $mediaFile, $host, $post, $head, $tail)
    {
        $sock = fsockopen("ssl://".$host, 443);

        fwrite($sock, $post);
        fwrite($sock, $head);

        //write file data
        $buf = 1024;
        $totalread = 0;
        $fp = fopen($mediaFile->getFilepath(), "r");
        while ($totalread < $mediaFile->getSize()) {
            $buff = fread($fp, $buf);
            fwrite($sock, $buff, $buf);
            $totalread += $buf;
        }
        //echo $TAIL;
        fwrite($sock, $tail);
        sleep(1);

        $data = fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        $data .= fgets($sock, 8192);
        fclose($sock);

        list($header, $body) = preg_split("/\R\R/", $data, 2);

        $json = json_decode($body, true);
        if (!is_array($json)) {
            return $json;
        }

        return false;
    }
}
