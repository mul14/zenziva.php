<?php

namespace Nasution\ZenzivaSms;

use Requests;

class Client
{
    const TIMEOUT = 60;
    const TYPE_OTP = 'otp';
    const SCHEME = 'https';
    const SUBDOMAIN_REGULER = 'reguler';
    const SUBDOMAIN_MASKING = 'alpha';

    /**
     * Zenziva end point
     *
     * @var string
     */
    protected $url = '{scheme}://{subdomain}.zenziva.net/apps/smsapi.php';

    /**
     * Zenziva username
     *
     * @var string
     */
    protected $username;

    /**
     * Zenziva password
     *
     * @var string
     */
    protected $password;

    /**
     * Phone number
     *
     * @var string
     */
    public $to;

    /**
     * Message
     *
     * @var string
     */
    public $text;

    /**
     * Sub-domain
     *
     * @var string
     */
    public $subdomain = 'reguler';
    
    /**
     * URL scheme
     *
     * @var string
     */
    public $scheme = 'https';

    /**
     * SMS type. Masking or reguler.
     *
     * @var string
     */
    public $type = self::TYPE_REGULER;

    /**
     * Create the instance
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Change default URL or get current URL
     *
     * @param string $url
     */
    public function url($url = '')
    {
        if (!$url) {
            return $this->url;
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set destination phone number
     *
     * @param $to  Phone number
     *
     * @return self
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set messages
     *
     * @param $text  Message
     *
     * @return self
     */
    public function text($text)
    {
        if (! is_string($text)) {
            throw new \Exception('Text should be string type.');
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Set sub-domain
     *
     * @param $subdomain  Sub-domain
     *
     * @return self
     */
    public function subdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Set masking
     *
     * @param boolean $masking  Masking
     *
     * @return self
     */
    public function masking($masking = true)
    {
        $this->subdomain = $masking ? self::SUBDOMAIN_MASKING : self::SUBDOMAIN_REGULER;

        return $this;
    }

    /**
     * Set as OTP
     *
     * @param boolean $otp  OTP
     *
     * @return self
     */
    public function otp($otp = true)
    {
        $this->type = $otp ? self::TYPE_OTP : null;

        return $this;
    }
    
    /**
     * Set URL scheme
     *
     * @param $scheme  scheme
     *
     * @return self
     */
    public function scheme($scheme)
    {
        $this->scheme = $scheme == 'http' ? 'http' : self::SCHEME;

        return $this;
    }

    /**
     * @param $to  Phone number
     * @param $text  Message
     *
     * @return \Requests_Response
     * @throws \Exception
     */
    public function send($to = '', $text = '')
    {
        if (! is_string($text)) {
            throw new \Exception('Text should be string type.');
        }

        $this->to   = ! empty($to) ? $to : $this->to;
        $this->text = ! empty($text) ? $text : $this->text;

        if (empty($this->to)) {
            throw new \Exception('Destination phone number is empty.');
        }

        if (is_null($this->text)) {
            throw new \Exception('Text is not set.');
        }

        $url = $this->buildQuery();

        return $this->doRequest($url);
    }

    /**
     * @param  string $url
     * @return \Requests_Response
     */
    private function doRequest($url)
    {
        $options = [
            'timeout' => self::TIMEOUT,
        ];

        return Requests::get($url, [], $options);
    }

    /**
     * Build query string
     *
     * @return string
     */
    protected function buildQuery()
    {
        if (empty($this->subdomain)) {
            throw new \Exception('Sub domain is not set.');
        }

        $url = str_replace('{subdomain}', $this->subdomain, $this->url);
        $url = str_replace('{scheme}', $this->scheme, $url);
        
        $type = [];
        if ($this->type) {
            $type = [
                'type' => $this->type,
            ];
        }

        $params = http_build_query(array_merge([
            'userkey' => $this->username,
            'passkey' => $this->password,
            'nohp'    => $this->to,
            'pesan'   => $this->text,
        ], $type));

        $params = urldecode($params);

        return $url . '?' . $params;
    }
}
