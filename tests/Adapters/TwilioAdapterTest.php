<?php

namespace Rapltech\LaravelSms\Tests\Adapters;

use Rapltech\LaravelSms\Adapters\Twilio\TwilioAdapter;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;
use Rapltech\LaravelSms\Tests\Adapters\HttpClient as MockClient;
use Rapltech\LaravelSms\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class TwilioAdapterTest extends TestCase
{
    use MockClient;

    private $config;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config = [
           'sid' => bin2hex(random_bytes(40)),
           'token' => bin2hex(random_bytes(40)),
           'from' => '+141040013440',
        ];
    }

    #[Test]
    public function invalidCredentials()
    {
        $config = $this->config;

        $config['sid'] = null;

        $adapter = (new TwilioAdapter($config))->setClient($this->mockClient(400));

        $this->expectException(AdapterException::class);

        $adapter->send(new Device('+910123456789', 'IN'), 'Test message');
    }

    #[Test]
    public function invalidDevice()
    {
        $stub = [
            'code' => 21604,
            'message' => "A 'To' phone number is required.",
            'more_info' => 'https://www.twilio.com/docs/errors/21604',
            'status' => 400
        ];

        $adapter = (new TwilioAdapter($this->config))->setClient($this->mockClient(400, $stub));

        $response = $adapter->send(new Device('+910123456789', 'IN'), 'Test message');

        $this->assertSame(400, $response->getStatusCode());
        $this->assertInstanceOf(PsrResponseInterface::class, $response->getRaw());
        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isFailure());
        $this->assertEquals($stub['code'], $response->getErrorCode());
        $this->assertEquals($stub['message'], $response->getErrorMessage());
        $this->assertSame('Bad Request', $response->getReasonPhrase());
    }

    #[Test]
    public function successResponse()
    {
        $stub = [
            'sid' => 'SM05849065be86450c9c81b9ff101db634',
            'date_created' => 'Sat, 06 Jan 2018 08:26:00 +0000',
            'date_updated' => 'Sat, 06 Jan 2018 08:26:00 +0000',
            'date_sent' => null,
            'account_sid' => $this->config['sid'],
            'to' => '+910123456789',
            'from' => $this->config['from'],
            'messaging_service_sid' => null,
            'body' => 'Test message',
            'status' => 'queued',
            'num_segments' => '1',
            'num_media' => '0',
            'direction' => 'outbound-api',
            'api_version' => '2010-04-01',
            'price' => null,
            'price_unit' => 'USD',
            'error_code' => null,
            'error_message' => null,
            'uri' => '/2010-04-01/Accounts/AC1ab5d33d2932cad2635a3acb33c821e9/Messages/SM05849065be86450c9c81b9ff101db634.json',
        ];

        $adapter = (new TwilioAdapter($this->config))->setClient($this->mockClient(200, $stub));

        $response = $adapter->send(new Device('+910123456789', 'IN'), 'Test message');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertInstanceOf(PsrResponseInterface::class, $response->getRaw());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailure());
        $this->assertEquals($stub['error_code'], $response->getErrorCode());
        $this->assertEquals($stub['error_message'], $response->getErrorMessage());
        $this->assertSame('OK', $response->getReasonPhrase());
    }
}
