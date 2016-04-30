<?php
/**
 * Render a media image file
 *
 * Params:
 * - image: MediaFile instance
 * - imageOptions: Array of HtmlHelper::image() compatible options
 * - label: Header label
 * - actions: Array of action links: Each action requires the 3 parameters of HtmlHelper::link()
 *
 * @see HtmlHelper
 */
if (!isset($imageOptions)) $imageOptions = [];
if (!isset($actions)) $actions = [];
?>
<?php if (isset($label)): ?>
    <h4><?= $label ;?></h4>
<?php endif; ?>
<?php
if ($image) {
    echo $this->Html->image($image->url, $imageOptions) . '<br />';
    echo h($image->basename) . '<br />';
}
?>
<?php
foreach ($actions as $action):
    echo $this->Ui->link($action[0], $action[1], $action[2]);
endforeach;
?>