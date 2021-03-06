<?php

use PHPUnit\Framework\TestCase;
use Zend\Config\Config;
use Obullo\Config\Processor\Env as EnvProcessor;

class EnvTest extends TestCase
{
    public function getEnvProvider()
    {
        if (! getenv('AMQP_USERNAME')) {
            putenv('AMQP_USERNAME=guest');
            putenv('AMQP_PASSWORD=guest');
        }

        return [
            'host' => '127.0.0.1',
            'port' => 5672,
            'username' => 'env(AMQP_USERNAME)',
            'password' => 'env(AMQP_PASSWORD)',
            'vhost' => '/',
        ];
    }

    public function testCanResolveEnvValues()
    {
        $config = new Config($this->getEnvProvider(), true);

        $processor = new EnvProcessor();
        $processor->process($config);

        $this->assertEquals('127.0.0.1', $config->get('host'));
        $this->assertEquals(5672, $config->get('port'));
        $this->assertEquals('guest', $config->get('username'));
        $this->assertEquals('guest', $config->get('password'));
        $this->assertEquals('/', $config->get('vhost'));
    }
}
