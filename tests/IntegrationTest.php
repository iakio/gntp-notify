<?php

use iakio\GntpNotify\GNTP;
use iakio\GntpNotify\IO;

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
        $this->io = new IO($host, $port/*, true */);
    }

    // http://www.growlforwindows.com/gfw/help/gntp.aspx
    function test_simplenotify()
    {
        $gntp = new GNTP("app", $this->io);
        $result = $gntp->sendNotify("notifytype1", "title", "text", array('icon_file' => __DIR__ . '/resources/a.png'));
        $this->assertEquals("-OK", $result);
        $result = $gntp->sendNotify("notifytype2", "title", "text\r\ntext\r\ntext", array('icon_file' => __DIR__ . '/resources/b.png'));
        $this->assertEquals("-OK", $result);
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
