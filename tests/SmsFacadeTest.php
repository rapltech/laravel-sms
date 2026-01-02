<?php

namespace Linkstreet\LaravelSms\Tests;

use Linkstreet\LaravelSms\Facades\Sms as SmsFacade;
use Linkstreet\LaravelSms\SmsManager;
use PHPUnit\Framework\Attributes\Test;

class SmsFacadeTest extends TestCase
{
    #[Test]
    public function returnInstanceOfManager()
    {
        $this->assertInstanceOf(SmsManager::class, SmsFacade::create([]));
    }
}
