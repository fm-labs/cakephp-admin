<?php
declare(strict_types=1);

namespace Admin\Util;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Mailer\Email;

class AdminNotifier
{
    /**
     * @param string $message Notification message to send
     * @return void
     */
    public static function send($message)
    {
        try {
            $email = self::_createEmail();
            $email->send($message);
        } catch (\Exception $ex) {
            Log::critical('AdminNotifier::send: ' . $ex->getMessage(), ['admin']);
        }
    }

    /**
     * @param \Exception $ex Exception notification message to send
     * @return void
     */
    public static function sendException(\Exception $ex)
    {
        self::send($ex->getMessage());
    }

    /**
     * @return \Cake\Mailer\Email
     * @throws \Exception
     */
    protected static function _createEmail()
    {
        if (Email::getConfig('admin') !== null) {
            $email = new Email('admin');
        } elseif (Configure::check('Admin.AdminNotifier')) {
            $email = new Email(Configure::read('Admin.AdminNotifier'));
        } else {
            throw new \Exception("AdminNotifier email configuration is missing");
        }

        return $email;
    }
}
