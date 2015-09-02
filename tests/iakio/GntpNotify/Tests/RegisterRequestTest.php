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

    function test_send_register_request_with_resource_file()
    {
        $resource_id = md5("DUMMY\n");
        $this->mock->expects($this->atLeastOnce())
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

    function test_send_register_request_with_resource_url()
    {
        $this->mock->expects($this->atLeastOnce())
            ->method("send")
            ->withConsecutive(
                array("GNTP/1.0 REGISTER NONE"),
                array("Application-Name: unittest"),
                array("Application-Icon: http://localhost/resource1.jpg"),
                array("Notifications-Count: 1"),
                array(""),
                array("Notification-Name: name"),
                array("Notification-Icon: http://localhost/resource2.jpg"),
                array("Notification-Enabled: True"),
                array(""),
                array("")
            );

        $request = new RegisterRequest("unittest", array("icon_url" => "http://localhost/resource1.jpg"));
        $request->addNotification("name", array("icon_url" => "http://localhost/resource2.jpg"));
        $request->send($this->mock);
    }
}
