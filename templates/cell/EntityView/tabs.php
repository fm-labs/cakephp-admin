<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Sugar.Formatter'); ?>
<?php $this->loadHelper('Sugar.DataTable'); ?>
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
        //debug($assoc->getName() . " -> " . $assoc->getProperty());
        if ($assoc instanceof \Cake\ORM\Association) {

            if (!isset($data[$assoc->getProperty()])) {
                continue;
            }

            if (!array_key_exists($assoc->getName(), $related)) {
                continue;
            }

            $this->Tabs->add(['title' => $assoc->getName()]);

            switch($assoc->type()) {
                case \Cake\ORM\Association::ONE_TO_ONE:
                    $_entity = $data[$assoc->getProperty()]['value'];
                    if (!$_entity) {
                        echo "- NO RECORD -";
                        break;
                    }
                    echo $this->cell('Admin.EntityView', [ $_entity, [
                        //'model' => $assoc->target()->getAlias()
                    ] ]);

                    break;

                case \Cake\ORM\Association::ONE_TO_MANY:

                    $datatable = array_merge([
                        'model' => $assoc->getTarget(),
                        'data' => $data[$assoc->getProperty()]['value'],
                        'fieldsBlacklist' => [$assoc->getForeignKey()],
                        'filter' => false,
                    ], $related[$assoc->getName()]);

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