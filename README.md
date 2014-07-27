gntp-notify
===========

Simple GNTP notification library


# Usage

```
<?php
$gntp = new GNTP();
$gntp->sendNotify("appname", "type", "title", "text", array('icon_file' => 'a.png'));
```

```
<?php
$gntp = new GNTP();
$register = new RegisterRequest("appname");
$register->addNotification("type1", array("icon_file" => "a.png");
$register->addNotification("type2", array("icon_file" => "b.png");
$notify = new NotificationRequest("appname", "type1", "title", array("text" => "text");

$gntp->notifyOrRegister($notify, $register);
```
