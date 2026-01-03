<?php

namespace Rapltech\LaravelSms\Tests\Adapters;

use Rapltech\LaravelSms\Adapters\Kap\KapAdapter;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;
use Rapltech\LaravelSms\Tests\Adapters\HttpClient as MockClient;
use Rapltech\LaravelSms\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class KapAdapterTest extends TestCase
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
            'username' => rand(),
            'password' => rand(),
            'sender' => rand(),
            'telemarketer' => rand(),
        ];
    }

    #[Test]
    public function invalidCredentials()
    {
        $config = $this->config;

        $config['username'] = null;

        $adapter = (new KapAdapter($config))->setClient($this->mockClient(400));

        $this->expectException(AdapterException::class);

        $adapter->send(new Device('+910123456789', 'IN'), 'Test message');
    }

    #[Test]
    public function invalidDevice()
    {
        $stub = [
            'messages' => [
                [
                    'messageId' => '',
                    'to' => '0010123456789',
                    'status' => -13,
                ]
            ]
        ];

        $adapter = (new KapAdapter($this->config))->setClient($this->mockClient(200, $stub));

        $response = $adapter->send(new Device('0010123456789', 'IN'), 'Test message');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertInstanceOf(PsrResponseInterface::class, $response->getRaw());
        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isFailure());
        $this->assertEquals(-13, $response->getErrorCode());
        $this->assertNotEmpty($response->getErrorMessage());
        $this->assertSame('OK', $response->getReasonPhrase());
    }

    #[Test]
    public function successResponse()
    {
        $stub = [
            'messages' => [
                [
                    'messageId' => '43406163014203536863',
                    'to' => '910123456789',
                    'smsCount' => '1',
                    'status' => 0,
                ]
            ]
        ];

        $adapter = (new KapAdapter($this->config))->setClient($this->mockClient(200, $stub));

        $response = $adapter->send(new Device('+910123456789', 'IN'), 'Test message');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertInstanceOf(PsrResponseInterface::class, $response->getRaw());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailure());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNotEmpty($response->getErrorMessage());
        $this->assertSame('OK', $response->getReasonPhrase());
    }
}
