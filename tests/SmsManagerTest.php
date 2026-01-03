<?php

namespace Rapltech\LaravelSms\Tests;

use Rapltech\LaravelSms\Adapters\Adapter;
use Rapltech\LaravelSms\Adapters\Log\LogAdapter;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;
use Rapltech\LaravelSms\SmsManager;
use PHPUnit\Framework\Attributes\Test;

class SmsManagerTest extends TestCase
{
    private $config;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->config = config('sms');
    }

    #[Test]
    public function returnInstanceOfManager()
    {
        $this->assertInstanceOf(SmsManager::class, SmsManager::create([]));
    }

    #[Test]
    public function setInvalidConnectionString()
    {
        $manager = new SmsManager($this->config);

        $this->expectException(AdapterException::class);

        $manager->connection('viki');
    }

    #[Test]
    public function setValidConnectionString()
    {
        $manager = new SmsManager($this->config);

        $connection = $this->config['default'];

        $this->assertSame($manager->connection($this->config['default'])->toArray()['connection'], $connection);
    }

    #[Test]
    public function setDevice()
    {
        $device = new Device('+10123456789', 'US');

        $manager = new SmsManager($this->config);
        $m_device = $manager->to($device)->toArray()['device'];

        $this->assertSame($m_device->getNumber(), $device->getNumber());
        $this->assertSame($m_device->getCountryIso(), $device->getCountryIso());
    }

    #[Test]
    public function resolveConnection()
    {
        $manager = (new SmsManager($this->config))->connection($this->config['default']);

        $device = new Device('+10123456789', 'US');

        $this->assertSame($manager->resolveConnection($device), $this->config['default']);
    }

    #[Test]
    public function resolveDefaultConnection()
    {
        $manager = new SmsManager($this->config);

        $device = new Device('+10123456789', 'US');

        $this->assertSame($manager->resolveConnection($device), $this->config['default']);
    }

    #[Test]
    public function returnsConnectionAdapter()
    {
        $manager = new SmsManager($this->config);

        $adapter = $manager->getAdapter($this->config['default']);

        $default_adapter = $this->config['connections'][$this->config['default']]['adapter'];

        $this->assertInstanceOf(Adapter::find($default_adapter), $adapter);
    }

    #[Test]
    public function returnsLogAdapter()
    {
        $manager = new SmsManager(array_merge($this->config, ['enabled' => false]));

        $adapter = $manager->getAdapter($this->config['default']);

        $this->assertInstanceOf(LogAdapter::class, $adapter);
    }
}
