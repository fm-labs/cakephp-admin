<div class="settings index">

    <div class="form" style="max-width: 1000px;">

        <?= $this->Form->create(null, ['horizontal' => true]); ?>

        <?php foreach($result as $namespace => $settings): ?>

            <?php if ($group && $namespace != $group) continue; ?>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= h($namespace); ?></h3>
                </div>
                <div class="box-body">
                    <?php foreach($settings as $key => $setting): ?>
                        <?= $this->Form->input($namespace.'.'.$key, $setting['input']); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="submit">
            <?= $this->Form->button(__d('backend','Update settings')); ?>
        </div>
        <?= $this->Form->end(); ?>

    </div>

    <?php debug($result); ?>
</div>