<?php

namespace Tmv\WhatsApi\Service;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Identity;
use RuntimeException;

class IdentityService
{
    /**
     * @var string
     */
    protected $networkInfoPath;

    /**
     * @param $networkInfoPath
     */
    public function __construct($networkInfoPath)
    {
        if ($networkInfoPath) {
            $this->setNetworkInfoPath($networkInfoPath);
        }
    }

    /**
     * @return string
     */
    public function getNetworkInfoPath()
    {
        if (!$this->networkInfoPath) {
            $this->networkInfoPath = __DIR__ . '/../../data/networkinfo.csv';
        }

        return $this->networkInfoPath;
    }

    /**
     * @param  string $networkInfoPath
     * @return $this
     */
    public function setNetworkInfoPath($networkInfoPath)
    {
        $this->networkInfoPath = $networkInfoPath;

        return $this;
    }

    /**
     * Request a registration code from WhatsApp.
     *
     * @param Identity $identity
     * @param string   $method      Accepts only 'sms' or 'voice' as a value.
     * @param string   $carrier     Carrier name
     * @param string   $countryCode ISO Country Code, 2 Digit.
     * @param string   $langCode    ISO 639-1 Language Code: two-letter codes.
     *
     * @return object
     *                An object with server response.
     *                - status: Status of the request (sent/fail).
     *                - length: Registration code lenght.
     *                - method: Used method.
     *                - reason: Reason of the status (e.g. too_recent/missing_param/bad_param).
     *                - param: The missing_param/bad_param.
     *                - retry_after: Waiting time before requesting a new code.
     *
     * @throws RuntimeException
     */
    public function codeRequest(Identity $identity, $method = 'sms', $carrier = "T-Mobile5", $countryCode = null, $langCode = null)
    {
        $phone = $identity->getPhone();
        if ($countryCode == null && $phone->getIso3166() != '') {
            $countryCode = $phone->getIso3166();
        }
        if ($countryCode == null) {
            $countryCode = 'US';
        }
        if ($langCode == null && $phone->getIso639() != '') {
            $langCode = $phone->getIso639();
        }
        if ($langCode == null) {
            $langCode = 'en';
        }

        if (null !== $carrier) {
            $mnc = $this->detectMnc(strtolower($countryCode), $carrier);
        } else {
            $mnc = $phone->getMcc();
        }

        // Build the token.
        $token = $this->generateRequestToken($phone->getPhone());

        // Build the url.
        $host = 'https://'.Client::WHATSAPP_REQUEST_HOST;
        $query = [
            'in' => $phone->getPhone(),
            'cc' => $phone->getCc(),
            'id' => $identity->getIdentityToken(),
            'lg' => $langCode,
            'lc' => $countryCode,
            'sim_mcc' => $phone->getMcc(),
            'sim_mnc' => $mnc,
            'method' => $method,
            'token' => $token,
        ];

        $response = $this->getResponse($host, $query);

        if ($response['status'] != 'sent' && $response['status'] != 'ok') {
            if (isset($response['reason']) && $response['reason'] == "too_recent") {
                $minutes = round($response['retry_after'] / 60);
                throw new RuntimeException("Code already sent. Retry after $minutes minutes.");
            } else {
                throw new RuntimeException('There was a problem trying to request the code.');
            }
        }

        return $response;
    }

    /**
     * Register account on WhatsApp using the provided code.
     *
     * @param Identity $identity
     * @param integer  $code     Numeric code value provided on requestCode().
     *
     * @return object
     *                An object with server response.
     *                - status: Account status.
     *                - login: Phone number with country code.
     *                - pw: Account password.
     *                - type: Type of account.
     *                - expiration: Expiration date in UNIX TimeStamp.
     *                - kind: Kind of account.
     *                - price: Formatted price of account.
     *                - cost: Decimal amount of account.
     *                - currency: Currency price of account.
     *                - price_expiration: Price expiration in UNIX TimeStamp.
     *
     * @throws RuntimeException
     */
    public function codeRegister(Identity $identity, $code)
    {
        // Build the url.
        $host = 'https://'.Client::WHATSAPP_REGISTER_HOST;

        $query = [
            'cc' => $identity->getPhone()->getCc(),
            'in' => $identity->getPhone()->getPhone(),
            'id' => $identity->getIdentityToken(),
            'code' => $code,
            'lg' => $identity->getPhone()->getIso639() ?: 'en',
            'lc' => $identity->getPhone()->getIso3166() ?: 'US',
        ];

        $response = $this->getResponse($host, $query);

        if ($response['status'] != 'ok') {
            $message = 'An error occurred registering the registration code from WhatsApp. '.$response['reason'];
            throw new RuntimeException($message);
        }

        return $response;
    }

    /**
     * @todo: This doesn't work now. Fix this
     *
     * Check if account credentials are valid.
     *
     * WARNING: WhatsApp now changes your password everytime you use this.
     * Make sure you update your config file if the output informs about
     * a password change.
     *
     * @param  Identity $identity
     * @return array
     *                           An object with server response.
     *                           - status: Account status.
     *                           - login: Phone number with country code.
     *                           - pw: Account password.
     *                           - type: Type of account.
     *                           - expiration: Expiration date in UNIX TimeStamp.
     *                           - kind: Kind of account.
     *                           - price: Formatted price of account.
     *                           - cost: Decimal amount of account.
     *                           - currency: Currency price of account.
     *                           - price_expiration: Price expiration in UNIX TimeStamp.
     *
     * @throws \RuntimeException
     */
    public function checkCredentials(Identity $identity)
    {
        $host = 'https://'.Client::WHATSAPP_CHECK_HOST;
        $query = [
            'cc' => $identity->getPhone()->getCc(),
            'in' => $identity->getPhone()->getPhone(),
            'id' => $identity->getIdentityToken(),
            'lg' => $identity->getPhone()->getIso639() ?: 'en',
            'lc' => $identity->getPhone()->getIso3166() ?: 'US',
            'network_radio_type' => "1"
        ];

        $response = $this->getResponse($host, $query);

        if ($response['status'] != 'ok') {
            $message = 'An error occurred. '.$response['reason'];
            throw new \RuntimeException($message);
        }

        return $response;
    }

    /**
     * Get a decoded JSON response from Whatsapp server
     *
     * @param  string $host  The host URL
     * @param  array  $query A associative array of keys and values to send to server.
     * @return object NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit
     */
    protected function getResponse($host, array $query)
    {
        // Build the url.
        $url = $host.'?';
        foreach ($query as $key => $value) {
            $url .= $key.'='.$value.'&';
        }
        $url = rtrim($url, '&');

        // Open connection.
        $ch = curl_init();

        // Configure the connection.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, Client::WHATSAPP_USER_AGENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: text/json']);
        // This makes CURL accept any peer!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Get the response.
        $response = curl_exec($ch);

        // Close the connection.
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * @param  string $phone
     * @return string
     */
    protected function generateRequestToken($phone)
    {
        return md5("PdA2DJyKoUrwLw1Bg6EIhzh502dF9noR9uFCllGk1419900749520" . $phone);
    }

    /**
     * @param  string      $lc          LangCode
     * @param  string      $carrierName Name of the carrier
     * @return null|string
     */
    protected function detectMnc($lc, $carrierName)
    {
        $fp = fopen($this->getNetworkInfoPath(), 'r');
        $mnc = null;

        while ($data = fgetcsv($fp, 0, ',')) {
            if (($data[4] === $lc) && ($data[7] === $carrierName)) {
                $mnc = $data[2];
                break;
            }
        }

        if ($mnc == null) {
            $mnc = '000';
        }

        fclose($fp);

        return $mnc;
    }

    /**
     * @return string
     */
    public static function generateIdentity()
    {
        $bytes = strtolower(openssl_random_pseudo_bytes(20));

        return $bytes;
    }
}
