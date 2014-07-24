<?php

namespace iakio\growl;

class GNTP
{
    private $io;
    private $applicationName;
    private $notifications;
    private $applicationIcon;
    private $resources;

    public function __construct(IO $io)
    {
        $this->io = $io;
        $this->applicationName = '';
        $this->notifications = array();
        $this->resources = array();
    }

    public function applicationName($name)
    {
        $this->applicationName = $name;
        return $this;
    }

    public function applicationIcon($icon)
    {
        $this->applicationIcon = $icon;
        return $this;
    }

    public function addNotification($name, $icon = null)
    {
        $this->notifications[$name] = array('icon' => $icon);
        return $this;
    }

    public function register()
    {
        $this->resources = array();
        $this->io->connect();
        $this->io->send("GNTP/1.0 REGISTER NONE");
        $this->io->send("Application-Name: " . $this->applicationName);
        if ($this->applicationIcon) {
            $hash = $this->addResource($this->applicationIcon);
            $this->io->send("Application-Icon: x-growl-resource://" . $hash);
        }
        $this->io->send("Notifications-Count: " . count($this->notifications));
        $this->io->send("");
        foreach ($this->notifications as $name => $notification) {
            $this->io->send("Notification-Name: " . $name);
            if ($notification['icon']) {
                $hash = $this->addResource($notification['icon']);
                $this->io->send("Notification-Icon: x-growl-resource://" . $hash);
            }
            $this->io->send("Notification-Enabled: True");
            $this->io->send("");
        }
        foreach ($this->resources as $resource) {
            $this->io->send("Identifier: " . $resource['hash']);
            $this->io->send("Length: " . strlen($resource['bin']));
            $this->io->send("");
            $this->io->sendBin($resource['bin']);
            $this->io->send("");
        }
        $this->io->send("");

        $response = $this->waitAndClose();
        return $response;
    }

    public function notify($name, $title, $text)
    {
        $this->resources = array();
        $this->io->connect();
        $this->io->send("GNTP/1.0 NOTIFY NONE");
        $this->io->send("Application-Name: " . $this->applicationName);
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

    public function addResource($file)
    {
        $bin = file_get_contents($file);
        $hash = md5($bin);
        $this->resources[$file] = array('bin' => $bin, 'hash' => $hash);
        return $hash;
    }
}