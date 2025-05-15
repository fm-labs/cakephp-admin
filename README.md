# Admin plugin for CakePHP

[![Build Status](https://travis-ci.org/fm-labs/cakephp-admin.svg?branch=master)](https://travis-ci.org/fm-labs/cakephp-admin)
[![Latest Stable Version](https://poser.pugx.org/fm-labs/cakephp-admin/v/stable.svg)](https://packagist.org/packages/fm-labs/cakephp-admin)
[![Total Downloads](https://poser.pugx.org/fm-labs/cakephp-admin/downloads.svg)](https://packagist.org/packages/fm-labs/cakephp-admin)
[![License](https://poser.pugx.org/fm-labs/cakephp-admin/license.svg)](https://packagist.org/packages/fm-labs/cakephp-admin)

An administration plugin for CakePHP 5.x

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp-admin

# For CakePHP 4.x
composer require fm-labs/cakephp-admin:^4.0
```

### Enable in your ROOT/config/bootstrap.php

```php
Plugin::load('Admin', ['bootstrap' => true, 'routes' => true]);
```

### Customize your admin config

1. Copy default config from plugins/Admin/config/admin.default.php to ROOT/config/admin.php
2. Adjust configuration settings


## Features

* Admin user management
* Admin auth handling
* Admin theme built on top of Bootstrap (v5)
* Dashboard
* System Info
* Log viewer
* Cronjob Management


## Config/Settings

| Key                          | Config | Settings | Comment    |
|------------------------------|--------|----------|------------|
| Admin.path                   | x      | -        |            |
| Admin.theme                  | x      | -        |            |
| Admin.Dashboard.title        | x      | x        |            |
| Admin.Dashboard.skin         | x      | x        |            |
| Admin.Dashboard.url          | x      | -        |            |
| Admin.Dashboard.Panels       | x      | -        |            |
| Admin.Search.searchUrl       | x      | -        |            |
| Admin.Security.enable        | x      | x        |            |
| Admin.Security.forceSSL      | x      | x        |            |
| Admin.LogRotation            | x      | -        |            |
| Admin.Users                  | x      | -        |            |
| Admin.Auth                   | x      | -        |            |
| Admin.AdminLte.skin_class    | x      | x        | DEPRECATED |
| Admin.AdminLte.layout_class  | x      | x        | DEPRECATED |
| Admin.AdminLte.sidebar_class | x      | x        | DEPRECATED |

