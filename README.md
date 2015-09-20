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
* Semantic UI Admin templates
* Bake Semantic UI templates from bake shell
* Dashboard
* System Info
* Log viewer
* Cronjob Management
* ...