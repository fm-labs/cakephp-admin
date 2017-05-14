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
$this->loadHelper('Backend.Chosen');

/**
 * Toolbar
 */
$this->Toolbar->addLink(__('List'), ['action' => 'index'], ['class' => 'list']);
$this->Toolbar->addLink(__('Edit'), ['action' => 'edit', $entity->id], ['class' => 'edit']);
$this->Toolbar->addPostLink(__('Delete'), ['action' => 'delete', $entity->id], ['class' => 'delete']);
?>
<div class="view">
    <?= $this->cell('Backend.EntityView', [ $entity ], $viewOptions)->render(); ?>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
