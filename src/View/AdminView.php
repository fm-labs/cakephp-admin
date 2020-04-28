<?php
declare(strict_types=1);

namespace Admin\View;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\View;

/**
 * Class AdminView
 *
 * @package Admin\View
 */
class AdminView extends View
{
    public $layout = "Admin.admin";

    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        $this->loadHelper('Html', ['className' => '\Admin\View\Helper\AdminHtmlHelper']);
        $this->loadHelper('Form', ['className' => '\Admin\View\Helper\AdminFormHelper']);
        $this->loadHelper('Admin.Admin');

        $this->getEventManager()->dispatch(new Event('Admin.View.initialize', $this));
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(string $name, string $default = ''): string
    {
        $content = parent::fetch($name, '');


        if ($name != "content" && strlen($content) < 1) {
            $blocks = (array)Configure::read('Admin.Layout.admin.blocks.' . $name);
            foreach ($blocks as $item) {
                if (isset($item['element'])) {
                    $content .= $this->element($item['element']);
                } elseif (isset($item['cell'])) {
                    $content .= $this->cell($item['cell']);
                }
            }

            $event = $this->getEventManager()->dispatch(
                new Event('Admin.View.fetch', $this, ['name' => $name, 'content' => $content])
            );
            $content = $event->getData('content');
        }

        if (!$content) {
            $content = $default;
        }


        return $content;
    }
}
