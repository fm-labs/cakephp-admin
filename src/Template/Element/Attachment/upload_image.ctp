<div class="ui segment attachment attachment-image">
    <?php if (isset($image) && is_object($image)): ?>
    <?php
    //echo h($image) . '<br />';
    echo $this->Html->image($image->url, ['width' => 200, 'title' => $image, 'alt' => $image]);
    //echo $this->Form->input($image->uploadField, ['type' => 'file']);
    ?>
    <?php else: ?>
        No image selected yet ;(
    <?php endif; ?>
    <!--
    <?= $this->Form->create(null, ['type' => 'file']); ?>
    <?= $this->Form->button(__('Upload image')) ?>
    <?= $this->Form->end() ?>
    -->
</div>