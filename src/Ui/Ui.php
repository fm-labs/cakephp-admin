<?php
declare(strict_types=1);

namespace Admin\Ui;

use Cake\View\View;

class Ui
{
    /**
     * @var \Cake\View\View
     */
    protected $view;

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * Ui constructor.
     * @param \Cake\View\View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param string $block
     * @param UiElementInterface $uiElement
     * @return $this
     */
    public function add(string $block, UiElementInterface $uiElement)
    {
        $this->elements[$block][] = $uiElement;

        return $this;
    }

    /**
     * @param string $block
     * @return string
     */
    public function render(string $block): string
    {
        $html = "";
        if (isset($this->elements[$block])) {
            /** @var \Admin\Ui\UiElementInterface $element */
            foreach ($this->elements[$block] as $element) {
                //if ($this->view->elementExists($element->elementName())) {
                    $html .= $this->view->element($element->elementName(), $element->data());
                //}
            }
        }

        return $html;
    }
}
