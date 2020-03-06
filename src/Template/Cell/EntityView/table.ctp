<?php
use Cake\Core\Configure;
?>
<?php $this->loadHelper('Backend.Formatter'); ?>
<table class="entity-table table table-hover">
    <tbody>
    <?php foreach ($data as $field) : ?>
        <tr class="<?= $field['class']; ?>">
            <td style="width: 25%;">
                <?= h($field['label']); ?>
            </td>
            <td>
                <?= $this->Formatter->format($field['value'], $field['formatter'], $field['formatterArgs'], $entity); ?>
            </td>
            <?php if ($debug === true) : ?>
                <td class="right">
                    <small>
                        <?php //sprintf("(%s:%s)", gettype($field['value']), $field['formatter'] ) ?>
                    </small>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>