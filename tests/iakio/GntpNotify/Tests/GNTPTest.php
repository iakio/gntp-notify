<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\GNTP;

class GNTPTest extends \PHPUnit_Framework_TestCase {

    function test_simple_notify()
    {
        $io = $this->getMockBuilder("iakio\\GntpNotify\\IO")
            ->setMethods(array("connect", "disconnect", "send", "sendBin", "recv"))
            ->getMock();
        $io->expects($this->any())
            ->method("recv")
            ->willReturnOnConsecutiveCalls(
                "GNTP/1.0 -OK NONE",
                false
            );

        $gntp = new GNTP("unittest", $io);
        $result = $gntp->sendNotify("name", "title", "text");
        $this->assertEquals("-OK", $result);
    }
}
