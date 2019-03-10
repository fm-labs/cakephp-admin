# Backend plugin for CakePHP

A graphical administration backend boilerplate for CakePHP applications.

## Requirements

CakePHP v3.1+

## Dependencies

fm-labs/cakephp-user
fm-labs/cakephp-bootstrap

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp-backend
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


## Dependencies

Javascript libs

* backbone
* underscore
* jquery
* bootstrap
* html5shiv
* momentjs

Widget libs

* Chosen.js (selectbox)
* Select2
* Daterangepicker
* SumoSelect
* ImagePicker
* JqueryUi (only used for sortable table)
* TinyMce WYSIWYG Html Editor
* ACE Code Editor
* Bootstrap Switch
* Pickadate

Icon libs

* flag-icon-css
* fontawesome
* Ionicons

Other

* SweetAlert2 (pretty confirm and alert boxes)
* Toastr (pretty flash messages)
* JsTree
* DataTablesJS
* jqvmap (jquery vector maps)
* footable (experimental)