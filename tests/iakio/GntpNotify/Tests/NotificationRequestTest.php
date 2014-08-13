<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\NotificationRequest;

class NotificationRequestTest extends \PHPUnit_Framework_TestCase {

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
        $this->mock->expects($this->any())
            ->method("send")
            ->withConsecutive(
                array("GNTP/1.0 NOTIFY NONE"),
                array("Application-Name: unittest"),
                array("Notification-Name: name"),
                array("Notification-Title: title"),
                array("Notification-Text: text1"),
                array(""),
                array("")
            );

        $request = new NotificationRequest("unittest", "name", "title", array("text" => "text1"));
        $request->send($this->mock);
    }
}
