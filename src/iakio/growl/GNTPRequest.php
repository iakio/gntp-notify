<?php

namespace iakio\growl;

abstract class GNTPRequest
{
    private $resources = array();

    abstract function send(IO $io);

    protected function addResource($resource_file)
    {
        $bin = file_get_contents($resource_file);
        $this->resources[$resource_file] = array(
            'hash' => md5($bin),
            'bin' => $bin
        );
    }

    protected function getResouces()
    {
        return $this->resources;
    }

    protected function getResouce($resouce_file)
    {
        return $this->resources[$resouce_file];
    }

    /**
     * @param IO $io
     */
    protected function sendResources(IO $io)
    {
        foreach ($this->getResouces() as $resource) {
            $io->send("Identifier: " . $resource['hash']);
            $io->send("Length: " . strlen($resource['bin']));
            $io->send("");
            $io->sendBin($resource['bin']);
            $io->send("");
        }
    }
}
