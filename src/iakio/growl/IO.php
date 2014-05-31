<?php

namespace iakio\growl;

class IO
{
    public function __construct($host = 'localhost', $port = 23053)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function send($msg)
    {
        fwrite($this->fd, $msg . "\r\n");
    }

    public function recv()
    {
        if (false === ($msg = fgets($this->fd))) {
            return false;
        }
        return chop($msg, "\r\n");
    }

    public function connect()
    {
        $this->fd = fsockopen($this->host, $this->port);
    }

    public function disconnect()
    {
        fclose($this->fd);
    }
}
