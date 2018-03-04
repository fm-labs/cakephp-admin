<?php // $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="settings index">

    <div class="form">

        <?php // $this->Tabs->create(); ?>

        <?php foreach($result as $namespace => $settings): ?>


            <?php // $this->Tabs->add($namespace); ?>
            <h2><?= __d('backend',"{0} settings",$namespace); ?></h2>
            <table class="table">
                <tr>
                    <th>Key</th>
                    <th>Label</th>
                    <th>Default</th>
                    <th>Current</th>
                </tr>
            <?php foreach($settings as $key => $setting): ?>
                <tr>
                    <td style="width: 20%;"><?= h($key); ?></td>
                    <td style="width: 20%;"><?= h($setting['input']['label']); ?></td>
                    <td style="width: 20%;"><?= h($setting['input']['default']); ?></td>
                    <td style="width: 20%;"><?= h(\Cake\Core\Configure::read($namespace.'.'.$key)); ?></td>
                </tr>
            <?php endforeach; ?>
            </table>
            <hr />

        <?php endforeach; ?>

        <?php // echo $this->Tabs->render(); ?>

    </div>

    <?php debug($result); ?>
</div>