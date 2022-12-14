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
$viewOptions = (array) $this->get('viewOptions');

/**
 * Helpers
 */
;
$this->loadHelper('Sugar.DataTable');
$this->loadHelper('Bootstrap.Tabs');
?>
<div class="view">

    <div class="alert alert-default">
        <strong><?= __d('admin', 'Confirm deletion'); ?></strong>
        <p>
            <?= $this->Form->postButton(__d('admin', 'Permanently delete record'), ['action' => 'delete', $entity->id], [
                'data' => ['confirm' => true],
                'form' => [],
                'class' => 'btn btn-primary btn-danger btn-lg'
            ]); ?>
        </p>
    </div>


    <?php
    if ($this->fetch('content')) {
        echo $this->fetch('content');
    } else {
        echo $this->cell('Admin.EntityView', [ $entity ], $viewOptions)->render('table');
    }
    ?>

</div>