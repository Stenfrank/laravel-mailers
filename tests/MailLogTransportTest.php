<?php

namespace Stenfrank\LaravelMailers\Tests;

use Illuminate\Mail\Transport\LogTransport;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Swift_SmtpTransport;

class MailLogTransportTest extends TestCase
{
    public function testGetLogTransportWithConfiguredChannel()
    {
        $this->app['config']->set('mail.driver', 'log');

        $this->app['config']->set('mail.log_channel', 'mail');

        $this->app['config']->set('logging.channels.mail', [
            'driver' => 'single',
            'path' => 'mail.log',
        ]);

        $transport = app('mailer')->getSwiftMailer()->getTransport();

        $this->assertInstanceOf(LogTransport::class, $transport);

        $logger = $transport->logger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);

        $this->assertInstanceOf(Logger::class, $monolog = $logger->getLogger());
        $this->assertCount(1, $handlers = $monolog->getHandlers());
        $this->assertInstanceOf(StreamHandler::class, $handler = $handlers[0]);
    }

    public function testGetLogTransportWithConfiguredChannelDynamic()
    {
        $this->app['config']->set('mails.mailers', explode('|', 'log|other'));
        $this->app['config']->set('mails.transports', explode('|', 'log|smtp'));

        $this->app['config']->set('mail.log_channel', 'mail');

        $this->app['config']->set('logging.channels.mail', [
            'driver' => 'single',
            'path' => 'mail.log',
        ]);

        $transportLog = app('mailer')->getSwiftMailer()->getTransport();

        $this->assertInstanceOf(LogTransport::class, $transportLog);

        $logger = $transportLog->logger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);

        $this->assertInstanceOf(Logger::class, $monolog = $logger->getLogger());
        $this->assertCount(1, $handlers = $monolog->getHandlers());
        $this->assertInstanceOf(StreamHandler::class, $handler = $handlers[0]);

        $transportSmtp = $this->app['mail.manager']->mailer('other')->getSwiftMailer()->getTransport();

        $this->assertInstanceOf(Swift_SmtpTransport::class, $transportSmtp);
    }

    public function testGetLogTransportWithPsrLogger()
    {
        $this->app['config']->set('mail.driver', 'log');
        $logger = $this->app->instance('log', new NullLogger());

        $transportLogger = app('mailer')->getSwiftMailer()->getTransport()->logger();

        $this->assertEquals($logger, $transportLogger);
    }

    public function testGetLogTransportWithPsrLoggerDynamic()
    {
        $this->app['config']->set('mail.driver', 'log');
        $this->app['config']->set('mails.mailers', explode('|', 'logg|other'));
        $this->app['config']->set('mails.transports', explode('|', 'log|log'));

        $logger = $this->app->instance('log', new NullLogger());

        $transportLogger = app('mailer')->getSwiftMailer()->getTransport()->logger();

        $this->assertEquals($logger, $transportLogger);

        $transportOther = $this->app['mail.manager']->mailer('other')->getSwiftMailer()->getTransport()->logger();

        $this->assertEquals($logger, $transportOther);
    }
}
