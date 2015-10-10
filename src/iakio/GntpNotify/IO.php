<?php

namespace iakio\GntpNotify;

class IO
{
    const TIMEOUT = 3;
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
        if (@fwrite($this->fd, str_replace("\r\n", "\n", $msg) . "\r\n") === false) {
            throw new IOException("fwrite()");
        }
        if ($this->debug) {
            echo $msg . "\n";
        }
    }

    public function sendBin($msg)
    {
        if (@fwrite($this->fd, $msg . "\r\n") === false) {
            throw new IOException("fwrite()");
        }
        if ($this->debug) {
            echo bin2hex($msg) . "\n";
        }
    }

    public function recv()
    {
        if (false === ($msg = @fgets($this->fd))) {
            if (!@feof($this->fd)) {
                throw new IOException("fgets()");
            }
            return false;
        }
        if ($this->debug) {
            echo $msg;
        }
        return chop($msg, "\r\n");
    }

    public function connect()
    {
        $this->fd = @fsockopen($this->host, $this->port, $errno, $errmsg, static::TIMEOUT);
        if ($this->fd === false) {
            throw new IOException($errmsg, $errno);
        }
        stream_set_timeout($this->fd, static::TIMEOUT);
    }

    public function disconnect()
    {
        fclose($this->fd);
    }
}
