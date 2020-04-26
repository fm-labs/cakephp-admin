<?php
declare(strict_types=1);

namespace Backend\View;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\View;

/**
 * Class BackendView
 *
 * @package Backend\View
 */
class BackendView extends View
{
    public $layout = "Backend.admin";

    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        $this->loadHelper('Html', ['className' => '\Backend\View\Helper\BackendHtmlHelper']);
        $this->loadHelper('Form', ['className' => '\Backend\View\Helper\BackendFormHelper']);
        $this->loadHelper('Backend.Backend');

        $this->getEventManager()->dispatch(new Event('Backend.View.initialize', $this));
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(string $name, string $default = ''): string
    {
        $content = parent::fetch($name, '');


        if ($name != "content" && strlen($content) < 1) {
            $blocks = (array)Configure::read('Backend.Layout.admin.blocks.' . $name);
            foreach ($blocks as $item) {
                if (isset($item['element'])) {
                    $content .= $this->element($item['element']);
                } elseif (isset($item['cell'])) {
                    $content .= $this->cell($item['cell']);
                }
            }

            $event = $this->getEventManager()->dispatch(
                new Event('Backend.View.fetch', $this, ['name' => $name, 'content' => $content])
            );
            $content = $event->getData('content');
        }

        if (!$content) {
            $content = $default;
        }


        return $content;
    }
}
