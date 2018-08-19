<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;

if (!Plugin::loaded('User')) {
    die("User plugin missing");
}

Plugin::load('Bootstrap');
//Plugin::load('User');

