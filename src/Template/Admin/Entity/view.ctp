<?= $this->assign('heading', __('Entity Info for {0} ID:{1}', $modelClass, $entityId)); ?>
<div class="backend-entity-view backend-view view">
    <?= $this->cell('Backend.EntityView', [ $entity ], [
        'title' => false,
        'model' => $this->get('modelClass'),
    ]); ?>
</div>
