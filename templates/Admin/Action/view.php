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
$viewOptions = (array)$this->get('viewOptions');
$entity = $this->get('entity');

if (!$entity) {
    throw new \Exception('Entity not found');
}

$title = $this->get('title', get_class($entity));
$this->assign('title', $title);

/**
 * Helpers
 */
//;
$this->loadHelper('Admin.DataTable');
$this->loadHelper('Bootstrap.Tabs');
//$this->extend('Admin./Base/form');

?>
<div class="view">
    <?= $this->fetch('content'); ?>
    <?= $this->cell('Admin.EntityView', [ $entity ], $viewOptions)
        ->render('table');
    ?>

    <?= $this->cell('Admin.EntityRelated', [ $entity ], [
        'modelClass' => $this->get('modelClass'),
        'related' => $this->get('related'),
    ])->render('box');
    ?>

    <?php if ($this->get('tabs')) : ?>
        <?php $this->Tabs->create(); ?>
        <?php foreach ((array)$this->get('tabs') as $tabId => $tab) : ?>
            <?php $this->Tabs->add($tab['title'], $tab); ?>
        <?php endforeach; ?>
        <?php echo $this->Tabs->render(); ?>
    <?php endif; ?>

    <?php if (\Cake\Core\Configure::read('debug')) : ?>
        <?php //debug($entity); ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>

</div>
