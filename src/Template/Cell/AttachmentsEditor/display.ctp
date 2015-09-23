<div class="attachments editor cell">
    <h3><?= __('{0} Attachments', $attachment->model); ?></h3>

    <div class="ui grid">
        <div class="row">
            <div class="four wide column">
                <h4 class="ui header">Attached Files</h4>
                <div class="attachments">
                    <?php foreach ($attachments as $_attachment): ?>
                        <div class="attachment">
                            <div class="image">
                                <?= $this->Html->image('/media/gallery/' . $_attachment->filepath, [
                                    'title' => $_attachment->filename
                                ]); ?>
                                <?= ($_attachment->desc_text) ? '[desc]' : ''; ?>
                            </div>
                            <div class="actions">
                                <?= $this->Html->link('Remove',
                                    ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'detach', $_attachment->id]
                                ); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($attachments)): ?>
                        <?= __('No attachments selected yet.'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="twelve wide column">
                <div class="ui form">
                    <?php
                    $this->Form->addWidget('imageselect', ['Backend\View\Widget\ImageSelectWidget']);
                    echo $this->Form->create($attachment, [
                        'url' => ['plugin' => 'Backend', 'controller' => 'Attachments', 'action' => 'attach']
                    ]);
                    ?>
                    <h4 class="ui header">
                        Gallery
                        <?php
                        echo $this->Form->button(__('Add file'), ['type' => 'submit', 'class' => 'ui small button']);
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
                    echo $this->Form->input('desc_text', ['class' => 'htmltext']);
                    //echo $this->Form->input('filename');
                    ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>