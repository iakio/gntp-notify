gntp-notify
===========

Simple GNTP notification library


# Usage

```
<?php
    $gntp = new GNTP("appname", $this->io);
    $gntp->sendNotify("type", "title", "text", array('icon_file' => 'a.png'));
```
