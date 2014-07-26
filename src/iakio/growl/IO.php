<?php

namespace iakio\growl;

class IO
{
    private $host;
    private $port;
    private $fd;
    private $debug;

    public function __construct($host = 'localhost', $port = 23053, $debug = false)
    {
        $this->host = $host;
        $this->port = $port;
        $this->debug = $debug;
    }

    public function send($msg)
    {
        fwrite($this->fd, str_replace("\r\n", "\n", $msg) . "\r\n");
        if ($this->debug) echo $msg . "\n";
    }

    public function sendBin($msg)
    {
        fwrite($this->fd, $msg . "\r\n");
        if ($this->debug) echo bin2hex($msg) . "\n";
    }

    public function recv()
    {
        if (false === ($msg = fgets($this->fd))) {
            return false;
        }
        if ($this->debug) echo $msg;
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
