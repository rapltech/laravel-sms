<?php

namespace Rapltech\LaravelSms\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sms', include dirname(__FILE__).'/../src/Config/config.php');
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return ['Rapltech\LaravelSms\Providers\SmsServiceProvider'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageAliases($app)
    {
        return [
            'Sms' => 'Rapltech\LaravelSms\Facades\Sms'
        ];
    }
}
