<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * class PageReloadHelper
 */
class PageReloadHelper extends Helper
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'timeout' => 60,
        'infoBlock' => false
    ];

    /**
     * The enabled timeout value in seconds.
     * If set to FALSE, no page page reload script will be added.
     * @var bool|int
     */
    protected $_timeout = false;

    public function enable($timeout = null)
    {
        $this->_timeout = ($timeout !== null && $timeout !== true) ? (int) $timeout : (int) $this->config('timeout');
    }

    public function beforeLayout(Event $event)
    {
        if ($this->_timeout !== false && $this->_timeout > 0) {
            $event->subject()->Html->meta(['http-equiv' => 'refresh', 'content' => $this->_timeout], null, ['block' => true]);

            //$script = sprintf('setTimeout(function() { window.location.href = window.location.href; }, %s);', $this->_timeout*1000);
            //$event->subject()->Html->scriptBlock($script, ['safe' => true, 'block' => true]);

        }

        if ($this->_config['infoBlock']) {
            $event->subject()->append($this->_config['infoBlock'],
                sprintf('<div class="pagereload-info">Auto-reload in %s seconds</div>', $this->_timeout)
            );
        }
    }
}
