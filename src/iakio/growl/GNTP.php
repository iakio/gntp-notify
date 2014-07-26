<?php

namespace iakio\growl;

class GNTP
{
    private $io;
    private $applicationName;

    public function __construct($applicationName, IO $io = null)
    {
        if (empty($io)) {
            $this->io = new IO();
        } else {
            $this->io = $io;
        }
        $this->applicationName = $applicationName;
    }

    /**
     * Shortcut method
     *
     * @param string $notificationName
     * @param string $notificationTitle
     * @param string $notificationText
     * @param array $options
     * @return string
     */
    public function sendNotify($notificationName, $notificationTitle, $notificationText, $options = array())
    {
        $notification_options = $options;
        $notification_options['text'] = $notificationText;
        $notify = new NotificationRequest($this->applicationName, $notificationName, $notificationTitle, $notification_options);
        $result = $this->send($notify);
        if ($result->getErrorCode() === "401" or $result->getErrorCode() === "402") {
            // UNKNOWN_APPLICATION or UNKNOWN_NOTIFICATION
            $register = new RegisterRequest($this->applicationName);
            $register->addNotification($notificationName);
            $result = $this->send($register);
            if ($result->getStatus() === "-OK") {
                $result = $this->send($notify);
            }
        }
        return $result->getStatus();
    }


    public function send(GNTPRequest $request)
    {
        $this->io->connect();
        $request->send($this->io);

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
        $response = new GNTPResponse();
        $response->parse($lines);
        return $response;
    }
}
