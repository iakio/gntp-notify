<?php

namespace iakio\GntpNotify;

class GNTP
{
    private $io;

    public function __construct(IO $io = null)
    {
        if (empty($io)) {
            $this->io = new IO();
        } else {
            $this->io = $io;
        }
    }

    /**
     * @param NotificationRequest $notify
     * @param RegisterRequest $register
     * @return GNTPResponse
     */
    public function notifyOrRegister(NotificationRequest $notify, RegisterRequest $register)
    {
        $result = $this->send($notify);
        if ($result->getErrorCode() === "401" or $result->getErrorCode() === "402") {
            // UNKNOWN_APPLICATION or UNKNOWN_NOTIFICATION
            $result = $this->send($register);
            if ($result->getStatus() === "-OK") {
                $result = $this->send($notify);
            }
        }
        return $result;
    }

    /**
     * Shortcut method
     *
     * @param string $applicationName
     * @param string $notificationName
     * @param string $notificationTitle
     * @param string $notificationText
     * @param array $options
     * @return string
     */
    public function sendNotify($applicationName, $notificationName, $notificationTitle, $notificationText, $options = array())
    {
        $notification_options = $options;
        $notification_options['text'] = $notificationText;
        $register = new RegisterRequest($applicationName);
        $register->addNotification($notificationName);
        $notify = new NotificationRequest($applicationName, $notificationName, $notificationTitle, $notification_options);
        return $this->notifyOrRegister($notify, $register)->getStatus();
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
