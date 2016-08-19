<?php

namespace Nasution\ZenzivaSms;

use Requests;

class Client
{
    /**
     * Zenziva end point
     *
     * @var string
     */
    protected $url = 'https://{subdomain}.zenziva.net/apps/{filename}.php';

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
     * File name
     *
     * @var string
     */
    public $filename;

    /**
     * SMS type. Masking or reguler.
     *
     * @var string
     */
    public $type = 'reguler';

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
     * Create the instance
     *
     * @param string $username
     * @param string $password
     *
     * @return self
     */
    public static function make($username, $password)
    {
        return new static($username, $password);
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
        $this->type = $masking ? 'masking' : 'reguler';

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
        $this->filename = 'smsapi';

        if (empty($this->to))
        {
            throw new \Exception('Destination phone number is empty.');
        }

        $url = $this->buildQuery();

        return Requests::get($url);
    }

    /**
     * @param $to  Group name
     * @param $text  Message
     *
     * @return \Requests_Response
     * @throws \Exception
     */
    public function sendToGroup($to = '', $text = '')
    {
        $this->to   = ! empty($to) ? $to : $this->to;
        $this->text = ! empty($text) ? $text : $this->text;
        $this->filename = 'sendsmsgroup';

        if (empty($this->to))
        {
            throw new \Exception('Group name is empty.');
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
        $url = $this->url;
        $url = str_replace('{subdomain}', $this->subdomain, $url);
        $url = str_replace('{filename}', $this->filename, $url);

        $params = [
            'userkey' => $this->username,
            'passkey' => $this->password,
            'tipe'    => $this->type,
            'pesan'   => $this->text,
        ];

        if ($this->filename === 'smsapi') {
            $params['nohp'] = $this->to;
        }

        if ($this->filename === 'sendsmsgroup') {
            $params['grup'] = $this->to;
        }

        $query = http_build_query($params);

        $query = urldecode($query);

        return $url . '?' . $query;
    }
}
