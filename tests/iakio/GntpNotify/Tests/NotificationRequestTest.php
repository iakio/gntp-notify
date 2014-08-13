<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\NotificationRequest;

class NotificationRequestTest extends \PHPUnit_Framework_TestCase
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

    function test_send_notification_request()
    {
        $resource_id = md5("DUMMY\n");
        $this->mock->expects($this->any())
            ->method("send")
            ->withConsecutive(
                array("GNTP/1.0 NOTIFY NONE"),
                array("Application-Name: unittest"),
                array("Notification-Name: name"),
                array("Notification-Title: title"),
                array("Notification-Text: text1"),
                array("Notification-Icon: x-growl-resource://$resource_id"),
                array(""),
                array("Identifier: $resource_id"),
                array("Length: 6"),
                array(""),
                array(""),
                array("")
            );
        $this->mock->expects($this->once())
            ->method("sendBin")
            ->with("DUMMY\n");

        $request = new NotificationRequest("unittest", "name", "title", array(
            "text" => "text1",
            "icon_file" => __DIR__ . "/dummy.txt"
        ));
        $request->send($this->mock);
    }
}
