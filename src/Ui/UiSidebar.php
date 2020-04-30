<?php
declare(strict_types=1);

namespace Admin\Ui;

use Cake\Utility\Inflector;

abstract class UiSidebar implements UiElementInterface
{
    public $plugin = null;

    public $elementName = null;

    /**
     * Get the title for the panel.
     *
     * @return string
     */
    public function title(): string
    {
        [$ns, $name] = namespaceSplit(static::class);
        $name = substr($name, 0, strlen('Panel') * -1);

        return Inflector::humanize(Inflector::underscore($name));
    }

    /**
     * Get the element name for the panel.
     *
     * @return string
     */
    public function elementName(): string
    {
        if ($this->elementName) {
            return $this->elementName;
        }

        [$ns, $name] = namespaceSplit(static::class);
        $elementName = 'ui/panel/' . Inflector::underscore($name);
        if ($this->plugin) {
            $elementName = $this->plugin . '.' . $elementName;
        }

        return $elementName;
    }

    /**
     * Get the data for the panel.
     *
     * @return array
     */
    public function data(): array
    {
        return [];
    }
}
