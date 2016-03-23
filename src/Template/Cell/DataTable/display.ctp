<?php
use Cake\Utility\Hash;
?>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <?php foreach ($params['headers'] as $header): ?>
        <th><?= h($header); ?></th>
        <?php endforeach; ?>
        <th class="actions"><?= __d('shop','Actions') ?></th>
    </thead>

    <tbody>
    <?php foreach ($params['data'] as $row): ?>
        <tr>
            <?php foreach ($params['headers'] as $header): ?>
            <td><?= h(Hash::get($row, $header)); ?></td>
            <?php endforeach; ?>
            <td class="actions">

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php // debug($params); ?>