<?php $this->Breadcrumbs->add(__d('banana','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('banana','New {0}', __d('banana','Setting'))); ?>
<div class="settings">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __d('banana','List {0}', __d('banana','Settings')),
                    ['action' => 'index'],
                    ['class' => 'item', 'data-icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
                                <div class="item">No Actions</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($setting, ['class' => 'setting']); ?>
    <h2 class="ui top attached header">
        <?= __d('banana','Add {0}', __d('banana','Setting')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('ref');
                echo $this->Form->input('scope');
                echo $this->Form->input('name');
                echo $this->Form->input('type', ['class' => 'sv-type']);
                echo $this->Form->input('value_int', ['class' => 'sv sv-int']);
                echo $this->Form->input('value_double', ['class' => 'sv sv-double']);
                echo $this->Form->input('value_string', ['class' => 'sv sv-string']);
                echo $this->Form->input('value_text', ['class' => 'sv sv-text']);
                echo $this->Form->input('value_boolean', ['class' => 'sv sv-boolean']);
                //echo $this->Form->input('value_datetime');
                echo $this->Form->input('description');
                echo $this->Form->input('published');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>
<?php $this->append('script-bottom'); ?>
<script>
$(document).ready(function() {

});
</script>
<?php $this->end(); ?>