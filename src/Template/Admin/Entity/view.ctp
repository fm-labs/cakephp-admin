<?php $this->assign('heading', __d('backend','Entity Info for {0} ID:{1}', $modelName, $modelId)); ?>
<div class="backend-entity-view backend-view view">
    <?php echo $this->cell('Backend.EntityView', [ $entity ], [
        'title' => false,
        'model' => $this->get('modelName'),
    ]);  ?>
</div>
