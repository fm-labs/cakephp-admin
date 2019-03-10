<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Backend.Formatter'); ?>
<?php $this->loadHelper('Backend.DataTable'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="entity-view">

    <?php $this->Tabs->create(); ?>

    <!-- Entity -->
    <?php $this->Tabs->add(['title' => $title]); ?>
    <dl class="dl-horizontal dl-striped">
        <?php foreach ($data as $field): ?>

            <dt class="<?= $field['class']; ?>">
                <?= h($field['label']); ?>
            </dt>
            <dd>
                <?= $this->Formatter->format( $field['value'], $field['formatter'], $field['formatterArgs'], $entity ); ?>
            </dd>
        <?php endforeach; ?>
    </dl>

    <!-- Related -->
    <?php foreach ($associations as $assoc): ?>
        <?php
        //debug($assoc->name() . " -> " . $assoc->property());
        if ($assoc instanceof \Cake\ORM\Association) {

            if (!isset($data[$assoc->property()])) {
                continue;
            }

            if (!array_key_exists($assoc->name(), $related)) {
                continue;
            }

            $this->Tabs->add(['title' => $assoc->name()]);

            switch($assoc->type()) {
                case \Cake\ORM\Association::ONE_TO_ONE:
                    $_entity = $data[$assoc->property()]['value'];
                    if (!$_entity) {
                        echo "- NO RECORD -";
                        break;
                    }
                    echo $this->cell('Backend.EntityView', [ $_entity, [
                        //'model' => $assoc->target()->alias()
                    ] ]);

                    break;

                case \Cake\ORM\Association::ONE_TO_MANY:

                    $datatable = array_merge([
                        'model' => $assoc->target(),
                        'data' => $data[$assoc->property()]['value'],
                        'fieldsBlacklist' => [$assoc->foreignKey()],
                        'filter' => false,
                    ], $related[$assoc->name()]);

                    $this->DataTable->create($datatable);
                    echo $this->DataTable->render();
                    break;
            }

        }
        ?>
    <?php endforeach; ?>

    <!-- Debug -->
    <?php $this->Tabs->add('Debug', ['debugonly' => true]); ?>
    <div class="debug">
        <?php debug($data); ?>
        <?php debug($entity->toArray()); ?>
        <?php debug($related); ?>
        <?php debug($associations); ?>
        <?php debug($schema); ?>
    </div>

    <?php echo $this->Tabs->render(); ?>

</div>