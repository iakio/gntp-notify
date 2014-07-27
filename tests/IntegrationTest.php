<?php

use iakio\GntpNotify\GNTP;
use iakio\GntpNotify\IO;
use iakio\GntpNotify\NotificationRequest;
use iakio\GntpNotify\RegisterRequest;

/**
 * @group integration
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IO
     */
    private $io;

    function setUp()
    {
        $host = 'localhost';
        $port = 23053;
        $this->io = new IO($host, $port/*, true*/);
    }

    // http://www.growlforwindows.com/gfw/help/gntp.aspx
    function test_simplenotify()
    {
        $gntp = new GNTP($this->io);
        $result = $gntp->sendNotify("gntp-test-simple", "notifytype1", "title", "text", array('icon_file' => __DIR__ . '/resources/a.png'));
        $this->assertEquals("-OK", $result);
        $result = $gntp->sendNotify("gntp-test-simple", "notifytype2", "title", "text\r\ntext\r\ntext", array('icon_file' => __DIR__ . '/resources/b.png'));
        $this->assertEquals("-OK", $result);
    }

    function test_multiple()
    {
        $gntp = new GNTP($this->io);
        $register = new RegisterRequest("gntp-test-multiple");
        $register->addNotification("notifytype1");
        $register->addNotification("notifytype2", array("icon_file" => __DIR__ . '/resources/c.png'));
        $notify = new NotificationRequest("gntp-test-multiple", "notifytype2", "title");
        $result = $gntp->notifyOrRegister($notify, $register);
        $this->assertEquals("-OK", $result->getStatus());
    }

    /**
     * @medium
     * @requires extension pcntl
     */
    function test_timeout()
    {
        $this->io->connect();
        $this->io->recv();
        $this->assertTrue(true);
    }
}
