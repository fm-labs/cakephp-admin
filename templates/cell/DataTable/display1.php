<?php
use Cake\Utility\Hash;

$this->loadHelper('Admin.DataTable');

$this->DataTable->create($params);
?>
<?= $this->DataTable->pagination(); ?>
<table class="<?= $this->DataTable->tableClass('table table-striped table-hover table-sm'); ?>">
    <thead>
        <?= $this->DataTable->renderHead(); ?>
        <th class="actions"><?= __d('admin','Actions') ?></th>
    </thead>

    <tbody>
    <?php foreach ($params['data'] as $row): ?>
        <tr data-id="<?= $row['id']; ?>">
            <?= $this->DataTable->renderRowCells($row); ?>
            <td class="actions">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                         <?= $this->DataTable->renderRowActions($params['rowActions'], $row); ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $this->DataTable->pagination(); ?>
<?= $this->DataTable->script(); ?>
<?= $this->DataTable->debug(); ?>