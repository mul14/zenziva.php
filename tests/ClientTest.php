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
            ->setMethods(['doRequest'])
            ->getMock();

        $sms->to('085225577999')->send();
    }

    public function test_text_with_empty_string_is_allowed_as_long_as_provided_explicitly()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(['send'])
            ->getMock();

        $sms->to('085225577999')->text('')->send();
    }

    /**
     * @dataProvider sendCalls
     * @expectedException \Exception
     * @expectedExceptionMessage Destination phone number is empty.
     */
    public function test_send_method_should_throw_exception_when_to_is_not_provided($case)
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(['text'])
            ->getMock();

        $case($sms);
    }

    /**
    * @return array
    */
    public function sendCalls()
    {
        return [
            [
                function (Sms $sms) {
                    $sms->expects($this->once())
                        ->method('text')
                        ->will($this->returnSelf());

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
                    $sms->expects($this->once())
                        ->method('text')
                        ->will($this->returnSelf());

                    $sms->text('Urgent message!')
                        ->send();
                }
            ],
            [
                function (Sms $sms) {
                    $sms->expects($this->once())
                        ->method('text')
                        ->will($this->returnSelf());

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
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(['doRequest'])
            ->getMock();

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

    /**
     * @dataProvider sendWithInvalidTextTypeCalls
     * @expectedException \Exception
     * @@expectedExceptionMessage Text should be string type.
     */
    public function test_text_should_be_type_of_string($call)
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(['doRequest'])
            ->getMock();
        
        $call($sms);
    }

    /**
     * @return array
     */
    public function sendWithInvalidTextTypeCalls()
    {
        return [
            [
                function (Sms  $sms) {
                    $sms->text(0)->send('085225575696');
                },
            ],
            [
                function (Sms  $sms) {
                    $sms->text(null)->send('085225575696');
                },
            ],
            [
                function (Sms  $sms) {
                    $sms->text(false)->send('085225575696');
                },
            ],
            [
                function (Sms  $sms) {
                    $sms->send('085225575696', false);
                },
            ],
            [
                function (Sms  $sms) {
                    $sms->send('085225575696', 1);
                },
            ],
            [
                function (Sms  $sms) {
                    $sms->send('085225575696', null);
                },
            ],
        ];
    }

    public function test_correct_usage_not_throw_any_error()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods([])
            ->setMethods(['send'])
            ->getMock();

        $sms->send('085225575999', 'Hello');
        $sms->to('085225575999')->text('Hello')->send();
        $sms->text('Hello')->send('085225575999');
        $sms->subdomain('app')->send('085225575999', 'Hello');
        $sms->subdomain('app')->to('085225575999')->text('Hello')->send();
        $sms->masking()->send('085225575999', 'Hello');
        $sms->masking()->text('Hello')->send('085225575999');
        $sms->masking()->text('Hello')->to('085225575999')->send();
        $sms->subdomain('app')->masking()->send('085225575999', 'Hello');
        $sms->subdomain('app')->masking()->text('Hello')->to('085225575999')->send();
    }
    
    public function test_buildQuery_method_should_works_properly_using_http_scheme()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->scheme('http')->text('')->to('085225577999');

        $reflection = new \ReflectionClass(get_class($sms));
        $buildQueryMethod = $reflection->getMethod('buildQuery');
        $buildQueryMethod->setAccessible(true);

        $this->assertEquals('http://reguler.zenziva.net/apps/smsapi.php?userkey=john&passkey=password&tipe=reguler&nohp=085225577999&pesan=', $buildQueryMethod->invoke($sms));
    }
    
    public function test_buildQuery_method_should_works_properly_using_http_scheme_and_subdomain()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->scheme('http')->subdomain('matriphe')->text('')->to('085225577999');

        $reflection = new \ReflectionClass(get_class($sms));
        $buildQueryMethod = $reflection->getMethod('buildQuery');
        $buildQueryMethod->setAccessible(true);

        $this->assertEquals('http://matriphe.zenziva.net/apps/smsapi.php?userkey=john&passkey=password&tipe=reguler&nohp=085225577999&pesan=', $buildQueryMethod->invoke($sms));
    }
    
    public function test_buildQuery_method_should_fallback_to_https_on_wrong_scheme()
    {
        $sms = $this->getMockBuilder(SMS::class)
            ->setConstructorArgs(['john', 'password'])
            ->setMethods(null)
            ->getMock();

        $sms->scheme('ftp')->text('')->to('085225577999');

        $reflection = new \ReflectionClass(get_class($sms));
        $buildQueryMethod = $reflection->getMethod('buildQuery');
        $buildQueryMethod->setAccessible(true);

        $this->assertEquals('https://reguler.zenziva.net/apps/smsapi.php?userkey=john&passkey=password&tipe=reguler&nohp=085225577999&pesan=', $buildQueryMethod->invoke($sms));
    }
}
