<?php

namespace Admin\Ui;

interface UiElementInterface
{
    /**
     * Get the title for the panel.
     *
     * @return string
     */
    public function title(): string;

    /**
     * Get the element name for the panel.
     *
     * @return string
     */
    public function elementName(): string;

    /**
     * Get the data for the panel.
     *
     * @return array
     */
    public function data(): array;
}
