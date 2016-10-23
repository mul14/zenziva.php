<?php

use Nasution\ZenzivaSms\Client as SMS;

class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function sendCalls()
    {
        return [
            [
                function (Sms $sms) {
                    $sms->send();
                }
            ],
            [
                function (Sms $sms) {
                    $sms->send('', 'Hello there!');
                }
            ],
            [
                function (Sms $sms) {
                    $sms->text('Urgent message!')
                        ->send();
                }
            ],
            [
                function (Sms $sms) {
                    $sms->text('Urgent message')
                        ->send();
                }
            ],
        ];
    }

    /**
     * @dataProvider sendCalls
     * @expectedException \Exception
     * @expectedExceptionMessage Destination phone number is empty.
     */
    public function test_send_method_should_throw_exception_when_to_is_not_provided($call)
    {
        $sms = new SMS('john', 'secretPassword');

        $call($sms);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Sub domain is not set.
     */
    public function test_send_method_should_throw_exception_when_sub_domain_is_not_set()
    {
        $sms = new SMS('john', 'secretPassword');

        $sms->subdomain('')
            ->text('Urgent message')
            ->to('085225577999')
            ->send();
    }
}