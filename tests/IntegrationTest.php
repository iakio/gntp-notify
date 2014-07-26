<?php

use iakio\growl\GNTP;
use iakio\growl\IO;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $io;

    function setUp()
    {
        $host = 'localhost';
        $port = 23053;
        $this->io = new IO($host, $port ,true);
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

}
