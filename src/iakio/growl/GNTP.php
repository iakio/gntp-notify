<?php

namespace iakio\growl;

class GNTP
{
    private $io;
    private $application_name;
    private $notifications;
    private $application_icon;

    public function __construct(IO $io)
    {
        $this->io = $io;
        $this->application_name = '';
        $this->notifications = array();
    }

    public function applicationName($name)
    {
        $this->application_name = $name;
        return $this;
    }

    public function applicationIcon($icon)
    {
        $this->application_icon = $icon;
        return $this;
    }

    public function addNotification($notification)
    {
        $this->notifications[] = $notification;
        return $this;
    }

    public function register()
    {
        $resources = [];
        $this->io->connect();
        $this->io->send("GNTP/1.0 REGISTER NONE");
        $this->io->send("Application-Name: " . $this->application_name);
        if ($this->application_icon) {
            $bin = file_get_contents($this->application_icon);
            $hash = md5($bin);
            $this->io->send("Application-Icon: x-growl-resource://" . $hash);
            $resources[$hash] = $bin;
        }
        $this->io->send("Notifications-Count: " . count($this->notifications));
        $this->io->send("");
        foreach ($this->notifications as $notification) {
            $this->io->send("Notification-Name: " . $notification);
            $this->io->send("Notification-Enabled: True");
            $this->io->send("");
        }
        foreach ($resources as $id => $resource) {
            $this->io->send("Identifier: " . $id);
            $this->io->send("Length: " . strlen($resource));
            $this->io->send("");
            $this->io->sendBin($resource);
            $this->io->send("");
        }
        $this->io->send("");

        $response = $this->waitAndClose();
        return $response;
    }

    public function notify($name, $title, $text)
    {
        $this->io->connect();
        $this->io->send("GNTP/1.0 NOTIFY NONE");
        $this->io->send("Application-Name: " . $this->application_name);
        $this->io->send("Notification-Name: " . $name);
        $this->io->send("Notification-Title: " . $title);
        $this->io->send("Notification-Text: " . $text);
        $this->io->send("");
        $this->io->send("");

        $response = $this->waitAndClose();
        return $response;
    }

    protected  function waitAndClose()
    {
        $lines = array();
        do {
            $line = $this->io->recv();
            $lines[] = $line;
        } while ($line !== false);
        $this->io->disconnect();
        if (preg_match('|^GNTP/1.0 -OK|', $lines[0])) {
            $response = "OK";
            return $response;
        } else {
            $response = "ERROR";
            return $response;
        }
    }
}