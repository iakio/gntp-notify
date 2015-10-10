<?php
namespace iakio\GntpNotify\Tests;

use iakio\GntpNotify\IO;

class IOTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \iakio\GntpNotify\IOException
     */
    public function test_throws_exception_if_connection_faild()
    {
        $io = new IO("", 0);
        $io->connect();
    }

    /**
     * @expectedException \iakio\GntpNotify\IOException
     */
    public function test_throws_exception_if_send_faild()
    {
        $io = new IO();
        $io->send("msg");
    }

    /**
     * @expectedException \iakio\GntpNotify\IOException
     */
    public function test_throws_exception_if_sendbin_faild()
    {
        $io = new IO();
        $io->sendBin("msg");
    }

    /**
     * @expectedException \iakio\GntpNotify\IOException
     */
    public function test_throws_exception_if_recv_faild()
    {
        $io = new IO();
        $io->recv();
    }
}
