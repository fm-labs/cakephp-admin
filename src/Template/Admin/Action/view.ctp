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
$title = $this->get('title', get_class($entity));
$viewOptions = (array) $this->get('viewOptions');

//$this->assign('title', $title);

/**
 * Helpers
 */
//$this->loadHelper('Backend.Chosen');
$this->loadHelper('Backend.DataTable');
$this->loadHelper('Bootstrap.Tabs');
$this->extend('Backend./Base/form');
?>
<div class="view">
    <?php
    echo ($this->fetch('content')) ?: $this->cell('Backend.EntityView', [ $entity ], $viewOptions)->render('table');
    ?>

    <!-- Related -->
    <?php foreach ($associations as $assoc) : ?>
        <?php
        //debug($assoc->name() . " -> " . $assoc->property());
        if ($assoc instanceof \Cake\ORM\Association) {
            if ($entity->get($assoc->property()) === null) {
                //debug("not set: " . $assoc->property());
                continue;
            }

            if (!array_key_exists($assoc->name(), $related)) {
                //debug("not enabled: " . $assoc->name());
                continue;
            }

            $relatedEntity = $entity->get($assoc->property());
            $title = __d('backend', 'Related {0}', $assoc->name());
            $html = __d('backend', "No data available");
            //$template = '<div class="box"><div class="box-header with-border">%s</div><div class="box-body">%s</div></div>';
            $template = '<div class="realted"><h3>%s</h3>%s</div>';

            switch ($assoc->type()) {
                case \Cake\ORM\Association::MANY_TO_ONE:
                case \Cake\ORM\Association::ONE_TO_ONE:
                    $config = ['title' => $title] + $related[$assoc->name()];
                    debug($config);
                    $html = $this->cell('Backend.EntityView', [ $relatedEntity ], $config);
                    $template = '%2$s';
                    break;

                case \Cake\ORM\Association::ONE_TO_MANY:
                    $dataTable = array_merge([
                        'model' => $assoc->target(),
                        'data' => $relatedEntity,
                        'fieldsBlacklist' => [$assoc->foreignKey()],
                        'filter' => false,
                        'actions' => false,
                        'rowActions' => false,
                    ], $related[$assoc->name()]);

                    $this->DataTable->create($dataTable);
                    $html = $this->DataTable->render();
                    break;

                case \Cake\ORM\Association::MANY_TO_MANY:
                default:
                    $html = __d('backend', 'Association type not implemented {0}', $type);
                    break;
            }

            echo sprintf($template, $title, $html);
        }
        ?>
    <?php endforeach; ?>

    <?php if ($this->get('tabs')): ?>
        <?php $this->Tabs->create(); ?>
        <?php foreach ((array) $this->get('tabs') as $tabId => $tab): ?>
            <?php $this->Tabs->add($tab['title'], $tab); ?>
        <?php endforeach; ?>
        <?php echo $this->Tabs->render(); ?>
    <?php endif; ?>

    <?php if (\Cake\Core\Configure::read('debug')) : ?>
        <?php //debug($entity); ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>


</div>
