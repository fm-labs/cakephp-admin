<?php

namespace Admin\System;

use _HumbugBox09702017065e\React\Socket\Connection;
use Cake\Datasource\ConnectionManager;

/**
 * Class Health
 *
 * ! Experimental !
 *
 * @package Admin\System
 */
class Health
{
    /**
     * @return array
     */
    public static function check(): array
    {
        $checks = [];
        $checks['core_permissions_write'] = function () {
            $dirs = [LOGS, CACHE, TMP];
            foreach ($dirs as $dir) {
                yield [sprintf("Directory writeable: %s", $dir), is_writable($dir)];
            }
        };
        $checks['core_permissions_read'] = function () {
            $dirs = [CONFIG, WWW_ROOT];
            foreach ($dirs as $dir) {
                yield [sprintf("Directory readable: %s", $dir), is_readable($dir)];
            }
        };
        $checks['db_connections'] = function () {
            foreach (ConnectionManager::configured() as $name) {
                //$config = ConnectionManager::getConfig($name);
                $ok = false;
                try {
                    $connection = ConnectionManager::get($name);
                    $connection->connect();
                    $ok = true;
                } catch (\Exception $ex) {
                } finally {
                    yield [sprintf("Connection '%s' status", $name), $ok];
                }
            }
        };

        $results = [];
        foreach ($checks as $check => $callable) {
            foreach (call_user_func($callable) as $result) {
                $results[$check][] = $result;
            }
        }

        return $results;
    }
}
