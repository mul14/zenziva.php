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
    protected $url = 'https://reguler.zenziva.net/apps/smsapi.php';

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

        if (empty($this->to))
        {
            throw new \Exception('Destination phone number is empty.');
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
        $params = http_build_query([
            'userkey' => $this->username,
            'passkey' => $this->password,
            'nohp'    => $this->to,
            'pesan'   => $this->text,
        ]);

        return $this->url . '?' . $params;
    }
}
