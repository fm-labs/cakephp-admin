<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Backend.Formatter'); ?>
<div class="entity-view">

    <?php if ($title): ?>
    <h1><?= h($title); ?></h1>
    <?php endif; ?>

    <table class="entity-table table table-hover">
        <tbody>
        <?php foreach ($data as $field): ?>
            <tr class="<?= $field['class']; ?>">
                <td>
                    <?= h($field['label']); ?>
                </td>
                <td>
                    <?= $this->Formatter->format( $field['value'], $field['formatter'], $field['formatterArgs'], $entity ); ?>
                </td>
                <?php if ($debug === true): ?>
                    <td class="right">
                        <small>
                            <?= sprintf("(%s:%s)", gettype($field['value']), $field['formatter'] ) ?>
                        </small>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($debug === true): ?>
    <div class="debug">
        <?php debug($associations); ?>
        <?php debug($schema); ?>
        <?php debug($data); ?>
        <?php debug($entity->toArray()); ?>
    </div>
    <?php endif; ?>
</div>