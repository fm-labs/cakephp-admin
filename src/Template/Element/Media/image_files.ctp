<?php
if (!isset($images)) $images = [];
if (!isset($imageOptions)) $imageOptions = [];
if (!isset($actions)) $actions = [];
?>
<?php if (isset($label)): ?>
    <h4><?= $label ;?></h4>
<?php endif; ?>
<?php
if (!empty($images)) {
    foreach ($images as $imageFile) {
        $this->element('Backend.Media/image_file', [
            'label' => false,
            'image' => $imageFile,
            'imageOptions' => $imageOptions,
            'actions' => []
        ]);
    }
}
?>
<?php
if (!empty($actions)) {
    foreach ($actions as $action):
        echo $this->Ui->link($action[0], $action[1], $action[2]);
    endforeach;
}
