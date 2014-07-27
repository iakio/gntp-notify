<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\GNTP;

class GNTPTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mock;

    function setUp()
    {
        $this->mock = $this->getMockBuilder("iakio\\GntpNotify\\IO")
            ->setMethods(array("connect", "disconnect", "send", "sendBin", "recv"))
            ->getMock();
    }

    function test_simple_notify()
    {
        $this->mock->expects($this->any())
            ->method("recv")
            ->will($this->onConsecutiveCalls(
                "GNTP/1.0 -OK NONE",
                false
            ));

        $gntp = new GNTP($this->mock);
        $result = $gntp->sendNotify("unittest", "name", "title", "text");
        $this->assertEquals("-OK", $result);
    }

    function test_register_and_retry_notification_if_application_is_unknown()
    {
        $this->mock->expects($this->any())
            ->method("recv")
            ->will($this->onConsecutiveCalls(
                "GNTP/1.0 -ERROR NONE",
                "Error-Code: 401",
                false,
                "GNTP/1.0 -OK NONE",
                false,
                "GNTP/1.0 -OK NONE",
                false
            ));

        $gntp = new GNTP($this->mock);
        $result = $gntp->sendNotify("unittest", "name", "title", "text");
        $this->assertEquals("-OK", $result);
    }

    function test_register_and_retry_notification_if_notification_is_unknown()
    {
        $this->mock->expects($this->any())
            ->method("recv")
            ->will($this->onConsecutiveCalls(
                "GNTP/1.0 -ERROR NONE",
                "Error-Code: 402",
                false,
                "GNTP/1.0 -OK NONE",
                false,
                "GNTP/1.0 -OK NONE",
                false
            ));

        $gntp = new GNTP($this->mock);
        $result = $gntp->sendNotify("unittest", "name", "title", "text");
        $this->assertEquals("-OK", $result);
    }
}
