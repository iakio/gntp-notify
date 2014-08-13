<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\RegisterRequest;

class RegisterRequestTest extends \PHPUnit_Framework_TestCase
{

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

    function test_send_register_request()
    {
        $this->mock->expects($this->any())
            ->method("send")
            ->withConsecutive(
                array("GNTP/1.0 REGISTER NONE"),
                array("Application-Name: unittest"),
                array("Notifications-Count: 1"),
                array(""),
                array("Notification-Name: name"),
                array("Notification-Enabled: True"),
                array(""),
                array("")
            );

        $request = new RegisterRequest("unittest", array());
        $request->addNotification("name");
        $request->send($this->mock);
    }
}
