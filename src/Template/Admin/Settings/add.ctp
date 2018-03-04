<?php $this->Breadcrumbs->add(__d('backend','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','New {0}', __d('backend','Setting'))); ?>
<?php $this->assign('title', __d('backend','Settings')); ?>
<?php $this->assign('heading', __d('backend','Add {0}', __d('backend','Setting'))); ?>
<div class="settings form">
    <?= $this->Form->create($setting, ['class' => 'setting']); ?>
    <?php
    echo $this->Form->input('scope');
    echo $this->Form->input('key');
    echo $this->Form->input('value');
    ?>
    <?= $this->Form->button(__d('backend','Submit')) ?>
    <?= $this->Form->end() ?>

</div>