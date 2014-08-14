<?php
namespace iakio\GntpNotify;

class RegisterRequest extends GNTPRequest
{
    private $applicationName;
    private $notifications;
    /**
     * @var array
     */
    private $options;

    public function __construct($applicationName, $options = array())
    {
        $this->applicationName = $applicationName;
        $this->options = $options;
        $this->notifications = array();
        if (isset($options['icon_file'])) {
            $this->addResource($options['icon_file']);
        }
    }

    public function addNotification($notificationName, $options = array())
    {
        $notification = $options;
        if (isset($options['icon_file'])) {
            $this->addResource($options['icon_file']);
        }
        $this->notifications[$notificationName] = $notification;
    }

    public function send(IO $io)
    {
        $io->send("GNTP/1.0 REGISTER NONE");
        $io->send("Application-Name: " . $this->applicationName);
        if (isset($this->options['icon_file'])) {
            $resource = $this->getResouce($this->options['icon_file']);
            $io->send("Application-Icon: x-growl-resource://" . $resource['hash']);
        }

        $io->send("Notifications-Count: " . count($this->notifications));
        $io->send("");
        foreach ($this->notifications as $name => $notification) {
            $io->send("Notification-Name: " . $name);
            if (isset($notification['icon_file'])) {
                $resource = $this->getResouce($notification['icon_file']);
                $io->send("Notification-Icon: x-growl-resource://" . $resource['hash']);
            }
            $io->send("Notification-Enabled: True");
            $io->send("");
        }
        $this->sendResources($io);
        $io->send("");
    }
}
