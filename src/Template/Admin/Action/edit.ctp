<?php
/**
 * View Action
 *
 * Renders entity with EntityView cell
 *
 * View variables:
 * - entity: Entity instance
 * - viewOptions: EntityView options array
 */
$entity = $this->get('entity');
//$title = $this->get('title', @array_pop(explode('\\', get_class($entity))));
$viewOptions = (array) $this->get('viewOptions');

$formOptions = $this->get('form.options', []);
$inputFields = $this->get('inputs.fields', []);
$inputOptions = $this->get('inputs.options', []);

debug($inputFields);

/**
 * Helpers
 */
$this->loadHelper('Backend.Chosen');
$this->loadHelper('Bootstrap.Tabs');
?>
<div class="edit">

    <div class="box">
        <?php if ($this->get('title')): ?>
        <div class="box-header with-border">
            <h3 class="box-title"><?= h($this->get('title')); ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-body">
            <div class="row">
                <div class="col-md-9">
                    <?php if ($this->fetch('content')) {
                        echo $this->fetch('content');
                    } else {
                        //echo $this->cell('Backend.EntityView', [ $entity ], $viewOptions)->render();

                        echo $this->Form->create($entity, $formOptions);
                        echo $this->Form->allInputs($inputFields, $inputOptions);
                        echo $this->Form->button(__('Submit'));
                        echo $this->Form->end();
                    }
                    ?>

                </div>
                <div class="col-md-3">
                    <?php
                    /*
                    foreach((array) $this->get('form_elements') as $element) {
                        $element += ['helpers' => null, 'cell' => null, 'element' => null];
                        foreach ((array) $element['helpers'] as $helper) {
                            $this->loadHelper($helper);
                        }
                        if (isset($element['cell'])) {
                            echo $this->cell($element['cell'], [['entity' => $entity, 'modelClass' => $modelClass]]);
                        } elseif (isset($element['element'])) {
                            echo $this->element($element['element'], ['entity' => $entity, 'modelClass' => $modelClass]);
                        }
                    }
                    */
                    ?>
                    <?php echo $this->fetch('form_elements'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->get('tabs')): ?>
        <?php $this->Tabs->create(); ?>
        <?php foreach ((array) $this->get('tabs') as $tabId => $tab): ?>
            <?php $this->Tabs->add($tab['title'], $tab); ?>
        <?php endforeach; ?>
        <?php echo $this->Tabs->render(); ?>
    <?php endif; ?>


    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
