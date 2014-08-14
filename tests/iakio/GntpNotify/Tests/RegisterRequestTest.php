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
        $resource_id = md5("DUMMY\n");
        $this->mock->expects($this->any())
            ->method("send")
            ->withConsecutive(
                array("GNTP/1.0 REGISTER NONE"),
                array("Application-Name: unittest"),
                array("Application-Icon: x-growl-resource://$resource_id"),
                array("Notifications-Count: 1"),
                array(""),
                array("Notification-Name: name"),
                array("Notification-Icon: x-growl-resource://$resource_id"),
                array("Notification-Enabled: True"),
                array(""),
                array("Identifier: $resource_id"),
                array("Length: 6"),
                array(""),
                // sendBin
                array(""),
                array("")
            );

        $this->mock->expects($this->once())
            ->method("sendBin")
            ->with("DUMMY\n");

        $request = new RegisterRequest("unittest", array("icon_file" => __DIR__ . "/dummy.txt"));
        $request->addNotification("name", array("icon_file" => __DIR__ . "/dummy.txt"));
        $request->send($this->mock);
    }
}
