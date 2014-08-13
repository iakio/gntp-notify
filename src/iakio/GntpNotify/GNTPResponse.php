<?php
namespace iakio\GntpNotify;

class GNTPResponse
{

    private $raw;
    private $status;
    private $encryption;
    private $error_code;
    private $error_description;

    public function parse(array $lines)
    {
        $this->raw = $lines;
        $matches = null;
        if (preg_match('|^GNTP/1.0 (.*) (.*)|', $lines[0], $matches)) {
            $this->status = $matches[1];
            $this->encryption = $matches[2];
        }
        foreach (array_slice($lines, 1) as $line) {
            if (!$line) {
                continue;
            }
            list($name, $value) = explode(": ", $line);

            $snake_cased_name = str_replace("-", "_", strtolower($name));
            if (property_exists($this, $snake_cased_name)) {
                $this->$snake_cased_name = $value;
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }
}
