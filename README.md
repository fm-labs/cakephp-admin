# Backend plugin for CakePHP

A graphical administration backend boilerplate for CakePHP applications.

## Requirements

CakePHP v3.1+

## Dependencies

fm-labs/cakephp3-user
fm-labs/cakephp3-semantic-ui

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp3-backend
```

- Enable in your ROOT/config/bootstrap.php

    Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);


- Copy default config from plugins/Backend/config/backend.default.php to ROOT/config/backend.php
    Edit configuration settings, if necessary


## Features

* Backend user management
* Backend auth handling
~~* Semantic UI Admin theme templates
~~* Bake Semantic UI templates from bake shell
* Bootstrap 3 Admin heme templates
* Dashboard
* System Info
* Log viewer
* Cronjob Management
* ...

## Config/Settings

| Key  | Config | Settings | Comment
|---|---|---|---|
|  Backend.path | x | - | |
|  Backend.theme | x | - | |
|  Backend.Dashboard.title | x | x | |
|  Backend.Dashboard.skin | x | x | |
|  Backend.Dashboard.url | x | - | |
|  Backend.Dashboard.Panels | x | - | |
|  Backend.Search.searchUrl | x | - | |
|  Backend.Security.enable | x | x | |
|  Backend.Security.forceSSL | x | x | |
|  Backend.LogRotation | x | - | |
|  Backend.Users | x | - | |
|  Backend.Auth | x | - | |
|  Backend.AdminLte.skin_class | x | x |DEPRECATED|
|  Backend.AdminLte.layout_class | x | x |DEPRECATED|
|  Backend.AdminLte.sidebar_class | x | x |DEPRECATED|
