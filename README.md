# Backend plugin for CakePHP

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

