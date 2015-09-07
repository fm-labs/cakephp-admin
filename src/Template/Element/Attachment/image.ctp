<div class="ui segments">
    <div class="ui segment">
        <?php if (isset($image) && is_array($image)): ?>
            <?php foreach ($image as $_image): ?>
            <?= $this->Html->image($_image->url, ['width' => 200, 'title' => $_image, 'alt' => $_image]); ?>
            <?php endforeach; ?>
        <?php elseif (isset($image) && is_object($image)): ?>
            <?= $this->Html->image($image->url, ['width' => 200, 'title' => $image, 'alt' => $image]); ?>
        <?php else: ?>
            <div style="padding: 3em; background-color: #333; color: #FFF; width: 200px;">
                No image selected yet ;(
            </div>
        <?php endif; ?>
    </div>
    <div class="ui segment">
        <button>Select from Media gallery</button>
        <!--
        <?= $this->Form->create(null, ['type' => 'file']); ?>
        <?= $this->Form->input($image->uploadField, ['type' => 'file']); ?>
        <?= $this->Form->end() ?>
        -->
    </div>
</div>