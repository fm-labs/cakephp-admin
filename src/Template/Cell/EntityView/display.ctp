<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Backend.Format'); ?>
<div class="entity-view">

    <?php if ($title): ?>
    <h1><?= h($title); ?></h1>
    <?php endif; ?>

    <table class="table table-hover table-entity">
        <thead>
        </thead>
        <tbody>
        <?php foreach ($entity->visibleProperties() as $field): ?>
            <?php if (in_array($field, $exclude)) continue; ?>
            <?php
            $val = $entity->get($field);
            $fieldTitle = (isset($fields[$field]) && isset($fields[$field]['title'])) ? $fields[$field]['title'] : Inflector::humanize($field);

            $column = $schema->column($field);

            $formatter = (isset($fields[$field]) && isset($fields[$field]['formatter'])) ? $fields[$field]['formatter'] : null;
            $formatterName = (is_string($formatter)) ? $formatter : gettype($formatter);

            $formattedValue = $this->Format->formatDataCell( $field, $val, $formatter, $entity );

            ?>
            <tr>
                <td>
                    <?= h($fieldTitle); ?>
                </td>
                <td>
                    <?= $formattedValue; ?>
                </td>
                <?php if (Configure::read('debug')): ?>
                <td class="right">
                    <small>
                        <?= sprintf("(%s:%s)", $column['type'], $formatterName ) ?>
                    </small>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="debug">
        <?php debug($entity->toArray()); ?>
        <?php //debug($fields); ?>
        <?php //debug($schema); ?>
    </div>
</div>