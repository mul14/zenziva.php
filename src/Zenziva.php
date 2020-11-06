<?php

namespace Nasution\Zenziva;

use Requests;

class Zenziva
{
    protected string $userkey;
    protected string $passkey;
    protected array $options;

    public function __construct(string $userkey, string $passkey, array $options = [])
    {
        $this->userkey = $userkey;
        $this->passkey = $passkey;

        $this->options = count($options)
            ? $options
            : [ 'domain' => '', 'masking' => false, 'whatsapp_id' => ''];
    }

    public function sms(string $phone, string $message): object {
        $url = $this->url() . '/sendsms/';

        return $this->send($url, [
            'nohp' => $phone,
            'pesan' => $message,
        ]);
    }

    public function wa(string $phone, string $message): object {
        $url = $this->url() . '/sendWA/';

        $payload = [
            'nohp' => $phone,
            'pesan' => $message,
        ];

        if ($this->options['whatsapp_id']) {
            $url = $this->url() . '/WAsendMsg/';
            $payload['instance'] = $this->options['whatsapp_id'];
        }

        return $this->send($url, $payload);
    }

    public function voice(string $phone, string $message): object {
        $url = 'https://console.zenziva.net/voice/api/sendvoice/';

        return $this->send($url, [
            'to' => $phone,
            'message' => $message,
        ]);
    }

    public function url(): string {
        $url = 'https://{domain}/api';

        if ($this->options['domain']) {
          return str_replace('{domain}', $this->options['domain'], $url);
        }

        if ($this->options['masking']) {
          return str_replace('{domain}', 'masking.zenziva.net', $url);
        }

        return str_replace('{domain}', 'gsm.zenziva.net', $url);
    }

    protected function send(string $url, array $payload = []): object {
        $response = Requests::post($url, [], $payload);

        return json_decode($response->body);
    }
}
