<?php
declare(strict_types=1);

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
        'timeout' => 0,
        'render' => 'html', // 'html' (default), 'script', 'both' (not recommended)
        'infoBlock' => false,
        'infoTemplate' => '<div class="pagereload-info"><span>Auto-reload in %s seconds</span> <a href="javascript:history.go(0)">Refresh page</a></div>',
    ];

    /**
     * The enabled timeout value in seconds.
     * If set to FALSE, no page page reload script will be added.
     * @var bool|int
     */
    protected $_timeout = 0;

    /**
     * Enable / Disable automatic page reload
     * @param bool|int $enable Boolean flag or timeout in seconds.
     *  If a numeric value is set, the page reload will be enabled with given value as timeout in seconds
     * @return void
     */
    public function enable($enable = true)
    {
        if (is_numeric($enable)) {
            $this->setTimeout($enable);
        } elseif ($enable) {
            $this->setTimeout($this->getConfig('timeout'));
        } else {
            $this->setTimeout(0);
        }
    }

    /**
     * Set timeout
     *
     * @param int $timeout Timeout in seconds
     * @return $this
     */
    public function setTimeout($timeout = 0)
    {
        $this->_timeout = (int)$timeout;

        return $this;
    }

    /**
     * Inject page reload before rendering layout
     *
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        if ($this->_timeout > 0) {
            if ($this->_config['render'] == 'html' || $this->_config['render'] == 'both') {
                $event->getSubject()->Html->meta(['http-equiv' => 'refresh', 'content' => $this->_timeout], null, ['block' => true]);
            }

            if ($this->_config['render'] == 'script' || $this->_config['render'] == 'both') {
                // for the javascript we need timeout in milliseconds
                $timeoutMs = $this->_timeout * 1000;

                // if both render methods are use, we use a little offset in the script,
                // in favor of the 'script' renderer, to make sure javascript triggers the reload
                // and not the 'html' renderer.
                if ($this->_config['render'] == 'both') {
                    $timeoutMs -= 500; // half a second should do the trick
                }

                $scriptTemplate = <<<SCRIPT
(function() {
var pagereload_timeout = setTimeout(function() {
  window.location.href = window.location.href;
}, {{TIMEOUT}});

window.onunload = function() { clearTimeout(pagereload_timeout); }
})();
SCRIPT;
                $script = str_replace(
                    ['{{TIMEOUT}}'],
                    [$timeoutMs],
                    $scriptTemplate
                );
                $event->getSubject()->Html->scriptBlock($script, ['safe' => true, 'block' => true]);
            }

            if ($this->_config['infoBlock']) {
                $block = $this->_config['infoBlock'] === true ? 'after' : $this->_config['infoBlock'];
                $event->getSubject()->append($block, sprintf($this->_config['infoTemplate'], $this->_timeout));
            }
        }
    }
}
