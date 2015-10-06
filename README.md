gntp-notify
===========
[![Build Status](https://travis-ci.org/iakio/gntp-notify.svg?branch=master)](https://travis-ci.org/iakio/gntp-notify)

Simple GNTP notification library


# Usage

```:php
<?php
$gntp = new GNTP();
$gntp->sendNotify("appname", "type", "title", "text", array('icon_file' => 'a.png'));
```

```:php
<?php
$gntp = new GNTP();
$register = new RegisterRequest("appname");
$register->addNotification("type1", array("icon_url" => "http://localhost/a.png");
$register->addNotification("type2", array("icon_file" => "b.png");
$notify = new NotificationRequest("appname", "type1", "title", array("text" => "text");

$gntp->notifyOrRegister($notify, $register);
```

# Requirements

- PHP >= 5.3.3

# License

MIT

# Links

* http://www.growlforwindows.com/gfw/help/gntp.aspx
