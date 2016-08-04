<?php namespace Nasution\Zenziva;

use Curl\Curl;

class Zenziva
{
    protected $baseURL = 'https://reguler.zenziva.net/apps/';

    protected $userkey, $passkey;

    public function __construct($userkey = null, $passkey = null)
    {
        if (!empty($credentials)) {
            $this->setup($userkey, $passkey);
        }
    }

    /**
     * Setup credentials
     */
    public function setup($userkey, $passkey)
    {
        $this->userkey = $userkey;
        $this->passkey = $passkey;
    }

    /**
     * Send to http request to Zenziva API
     * 
     * @param string $target
     * @param array $payload
     * @return null
     */
    protected static function send($credentials, $target, array $payload = [])
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);

        $credentials = array(
            'userkey' => $credentials['userkey'],
            'passkey' => $credentials['passkey'],
        );

        $query = '?' . http_build_query($credentials);

        $url = $this->baseURL . $target . $query;

        $curl->get($url);

        return $curl->response;
    }

    /**
     * Send SMS
     * @param string $phoneNumber Phone number
     * @param string $message Message to send
     */
    public static function sendSMS($phoneNumber, $message)
    {
        $data['nohp'] = $phoneNumber;
        $data['pesan'] = $message;

        $data = array(
            'nohp' => $phoneNumber,
            'pesan' => $message
        );

        return static::send('smsapi.php', $data);
    }

    /**
     * Check credit
     */
    public static function checkCredit()
    {
        return static::send('smsapibalance.php');
    }
}
