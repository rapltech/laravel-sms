<?php

namespace Rapltech\LaravelSms\Tests;

use Rapltech\LaravelSms\Facades\Sms as SmsFacade;
use Rapltech\LaravelSms\SmsManager;
use PHPUnit\Framework\Attributes\Test;

class SmsFacadeTest extends TestCase
{
    #[Test]
    public function returnInstanceOfManager()
    {
        $this->assertInstanceOf(SmsManager::class, SmsFacade::create([]));
    }
}
