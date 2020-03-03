<?php

namespace Backend\View;

use Banana\View\ViewModuleTrait;
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
    use ViewModuleTrait;

    public $helpers = ['Html', 'Form' => ['className' => 'Backend\View\Helper\BackendFormHelper']];

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $this->eventManager()->dispatch(new Event('Backend.View.initialize', $this));
    }

    public function fetch($name, $default = '')
    {
        $content = parent::fetch($name, '');

        if ($name != "content" && strlen($content) < 1) {
            $blocks = (array)Configure::read('Backend.Layout.admin.blocks.' . $name);
            foreach ($blocks as $item) {
                //$_block = (isset($content['block'])) ? $content['block'] : $name;
                if (isset($item['element'])) {
                    $content .= $this->element($item['element']);
                    //$event->getSubject()->append($_block, $event->getSubject()->element($content['element']));
                } elseif (isset($item['cell'])) {
                    $content .= $this->cell($item['cell']);
                    //$event->getSubject()->append($_block, $event->getSubject()->cell($content['cell']));
                }
            }

            $event = $this->eventManager()->dispatch(
                new Event('Backend.View.fetch', $this, ['name' => $name, 'content' => $content])
            );
            $content = $event->data['content'];
        }

        if (!$content) {
            $content = $default;
        }

        return /*'[' . $name . ']' . */$content;
    }
}
