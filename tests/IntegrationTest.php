<?php

use iakio\growl\GNTP;
use iakio\growl\IO;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $host = 'localhost';
        $port = 23053;
        $this->io = new IO($host, $port);
    }

    // http://www.growlforwindows.com/gfw/help/gntp.aspx
    function test_register()
    {
        $gntp = new GNTP($this->io);
        $gntp->applicationName("app")
            ->addNotification("notifytype1")
            ->register();
        $gntp->notify("notifytype1", "title", "text");
    }

}
