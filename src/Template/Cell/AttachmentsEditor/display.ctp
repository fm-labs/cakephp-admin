<div class="attachments editor cell">
    <h3><?= __('{0} Attachments', $attachment->model); ?></h3>

    <div class="ui grid attachments">
        <?php if (empty($attachments)): ?>
            <div class="row">
                <div class="sixteen wide column">
                    <?= __('No attachments selected yet.'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php foreach ($attachments as $_attachment): ?>
        <div class="row attachment">
            <div class="four wide column">
                <div class="image">
                    <?= $this->Html->image('/media/gallery/' . $_attachment->filepath, [
                        'title' => $_attachment->filename
                    ]); ?>
                </div>
                <div class="actions">
                    <?= $this->Html->link('Edit',
                        ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'edit', $_attachment->id]
                    ); ?> |
                    <?= $this->Html->link('Edit (en)',
                        ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'edit', $_attachment->id, 'locale' => 'en']
                    ); ?> |
                    <?= $this->Html->link('Remove',
                        ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'detach', $_attachment->id]
                    ); ?>
                </div>
            </div>
            <div class="twelve wide column">
                <?= h($_attachment->desc_text); ?>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="row">
            <div class="sixteen wide column">
                <div class="ui form">
                    <?php
                    $this->Form->addWidget('imageselect', ['Backend\View\Widget\ImageSelectWidget']);
                    echo $this->Form->create($attachment, [
                        'url' => ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'attach']
                    ]);
                    ?>
                    <h4 class="ui header">
                        <?php
                        echo $this->Form->button(__('Add Attachment'), ['type' => 'submit', 'class' => 'ui small button']);
                        ?>
                    </h4>
                    <div class="imageselect_wrap">
                        <?php
                        echo $this->Form->input('filepath', ['type' => 'imageselect', 'label' => false, 'options' => $files]);
                        ?>
                    </div>
                    <?php
                    echo $this->Form->hidden('model');
                    echo $this->Form->hidden('modelid');
                    echo $this->Form->hidden('scope');
                    echo $this->Form->hidden('type');
                    echo $this->Form->hidden('_locale', ['value' => $locale]);
                    echo "Locale: $locale<br />";
                    echo $this->Form->input('desc_text', ['class' => 'htmltext']);
                    //echo $this->Form->input('filename');
                    ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>