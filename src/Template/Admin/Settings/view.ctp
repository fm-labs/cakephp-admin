<?php $this->Breadcrumbs->add(__d('banana','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($setting->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','Edit {0}', __d('banana','Setting')),
                ['action' => 'edit', $setting->id],
                ['class' => 'item', 'data-icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('banana','Delete {0}', __d('banana','Setting')),
                ['action' => 'delete', $setting->id],
                ['class' => 'item', 'data-icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $setting->id)]) ?>

            <?= $this->Ui->link(
                __d('banana','List {0}', __d('banana','Settings')),
                ['action' => 'index'],
                ['class' => 'item', 'data-icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Setting')),
                ['action' => 'add'],
                ['class' => 'item', 'data-icon' => 'plus']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="settings view">
    <h2 class="ui top attached header">
        <?= h($setting->id) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('banana','Label'); ?></th>
            <th><?= __d('banana','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('banana','Ref') ?></td>
            <td><?= h($setting->ref) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Scope') ?></td>
            <td><?= h($setting->scope) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Name') ?></td>
            <td><?= h($setting->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Value String') ?></td>
            <td><?= h($setting->value_string) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($setting->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Type') ?></td>
            <td><?= $this->Number->format($setting->type) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Value Int') ?></td>
            <td><?= $this->Number->format($setting->value_int) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Value Double') ?></td>
            <td><?= $this->Number->format($setting->value_double) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('banana','Value Datetime') ?></td>
            <td><?= h($setting->value_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Created') ?></td>
            <td><?= h($setting->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Updated') ?></td>
            <td><?= h($setting->updated) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('banana','Value Boolean') ?></td>
            <td><?= $setting->value_boolean ? __d('banana','Yes') : __d('banana','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('banana','Published') ?></td>
            <td><?= $setting->published ? __d('banana','Yes') : __d('banana','No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('banana','Value Text') ?></td>
            <td><?= $this->Text->autoParagraph(h($setting->value_text)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('banana','Description') ?></td>
            <td><?= $this->Text->autoParagraph(h($setting->description)); ?></td>
        </tr>
    </table>
</div>
