# Admin plugin for CakePHP

A graphical administration boilerplate for CakePHP applications.

## Requirements

CakePHP: 4.0.x

## Dependencies

fm-labs/cakephp-user
fm-labs/cakephp-bootstrap

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp-admin
```

- Enable in your ROOT/config/bootstrap.php

    Plugin::load('Admin', ['bootstrap' => true, 'routes' => true]);


- Copy default config from plugins/Admin/config/admin.default.php to ROOT/config/admin.php
    Edit configuration settings, if necessary


## Features

* Admin user management
* Admin auth handling
* Admin theme built on top of Bootstrap (v5)
* Dashboard
* System Info
* Log viewer
* Cronjob Management

## Config/Settings

| Key  | Config | Settings | Comment
|---|---|---|---|
|  Admin.path | x | - | |
|  Admin.theme | x | - | |
|  Admin.Dashboard.title | x | x | |
|  Admin.Dashboard.skin | x | x | |
|  Admin.Dashboard.url | x | - | |
|  Admin.Dashboard.Panels | x | - | |
|  Admin.Search.searchUrl | x | - | |
|  Admin.Security.enable | x | x | |
|  Admin.Security.forceSSL | x | x | |
|  Admin.LogRotation | x | - | |
|  Admin.Users | x | - | |
|  Admin.Auth | x | - | |
|  Admin.AdminLte.skin_class | x | x |DEPRECATED|
|  Admin.AdminLte.layout_class | x | x |DEPRECATED|
|  Admin.AdminLte.sidebar_class | x | x |DEPRECATED|

