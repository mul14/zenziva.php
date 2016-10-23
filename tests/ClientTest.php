<?php

use Nasution\ZenzivaSms\Client as SMS;

class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Text is not set.
     */
    public function test_text_must_be_provided_explicitly()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->to('085225577999')->send();
    }

    public function test_text_with_empty_string_is_allowed_as_long_as_provided_explicitly()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->to('085225577999')->text('')->send();
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
     * @return array
     */
    public function sendCalls()
    {
        return [
            [
                function (Sms $sms) {
                    $sms->text('')->send();
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

    public function test_buildQuery_method_should_works_properly()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->text('')->to('085225577999');

        $reflection = new \ReflectionClass(get_class($sms));
        $buildQueryMethod = $reflection->getMethod('buildQuery');
        $buildQueryMethod->setAccessible(true);

        $this->assertEquals('https://reguler.zenziva.net/apps/smsapi.php?userkey=john&passkey=password&tipe=reguler&nohp=085225577999&pesan=', $buildQueryMethod->invoke($sms));

        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->text('Some message')->to('085225577999');

        $reflection = new \ReflectionClass(get_class($sms));
        $buildQueryMethod = $reflection->getMethod('buildQuery');
        $buildQueryMethod->setAccessible(true);

        $this->assertEquals('https://reguler.zenziva.net/apps/smsapi.php?userkey=john&passkey=password&tipe=reguler&nohp=085225577999&pesan=Some message', $buildQueryMethod->invoke($sms));
    }
}