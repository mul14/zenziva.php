<?php

namespace Nasution\ZenzivaSms;

use Requests;

class Client
{
    const TYPE_REGULER = 'reguler';
    const TYPE_MASKING = 'masking';

    /**
     * Zenziva end point
     *
     * @var string
     */
    protected $url = 'https://{subdomain}.zenziva.net/apps/smsapi.php';

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
        $this->type = $masking ? self::TYPE_MASKING : self::TYPE_REGULER;

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
        $this->to   = ! empty($to) ? $to : $this->to;
        $this->text = ! empty($text) ? $text : $this->text;

        if (empty($this->to)) {
            throw new \Exception('Destination phone number is empty.');
        }

        if (is_null($this->text)) {
            throw new \Exception('Text is not set.');
        }

        $url = $this->buildQuery();

        return Requests::get($url);
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

        $params = http_build_query([
            'userkey' => $this->username,
            'passkey' => $this->password,
            'tipe'    => $this->type,
            'nohp'    => $this->to,
            'pesan'   => $this->text,
        ]);
        
        $params = urldecode($params);

        return $url . '?' . $params;
    }
}
