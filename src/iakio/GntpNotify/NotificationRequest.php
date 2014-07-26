<?php
namespace iakio\GntpNotify;


class NotificationRequest extends GNTPRequest {

    private $applicationName;
    private $notificationName;
    private $notificationTitle;
    /**
     * @var array
     */
    private $options;

    public function __construct($applicationName, $notificationName, $notificationTitle, $options = array())
    {
        $this->applicationName = $applicationName;
        $this->notificationName = $notificationName;
        $this->notificationTitle = $notificationTitle;
        $this->options = $options;
        if (isset($options['icon_file'])) {
            $this->addResource($options['icon_file']);
        }
    }

    public function send(IO $io)
    {
        $io->send("GNTP/1.0 NOTIFY NONE");
        $io->send("Application-Name: " . $this->applicationName);
        $io->send("Notification-Name: " . $this->notificationName);
        $io->send("Notification-Title: " . $this->notificationTitle);
        if (isset($this->options['text'])) {
            $io->send("Notification-Text: " . $this->options['text']);
        }
        if (isset($this->options['icon_file'])) {
            $resource = $this->getResouce($this->options['icon_file']);
            $io->send("Notification-Icon: x-growl-resource://" . $resource['hash']);
        }
        $io->send("");
        $this->sendResources($io);
        $io->send("");
    }
}
