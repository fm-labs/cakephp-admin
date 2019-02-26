<?php
namespace Backend\View\Cell;

use Cake\View\Cell;

/**
 * EntityRelated cell
 */
class EntityRelatedCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['modelClass', 'entity', 'related'];

    public $modelClass;

    public $related = [];

    /**
     * Default display method.
     *
     * @param EntityInterface $entity Entity object
     * @return void
     */
    public function display($entity)
    {
        /* @var \Cake\ORM\Table $Model */
        $Model = $this->loadModel($this->modelClass);

        $elements = [];
        foreach ($this->related as $alias => $relatedConfig) {
            if (is_numeric($alias)) {
                $alias = $relatedConfig;
                $relatedConfig = [];
            }
            $assoc = $Model->association($alias);
            if ($assoc) {
                if ($entity->get($assoc->property()) === null) {
                    debug($assoc->type() . ": associated property not set: " . $assoc->property());
                    continue;
                }

                $relatedEntity = $entity->get($assoc->property());
                $title = __d('backend', 'Related {0}', $assoc->name());

                switch ($assoc->type()) {
                    case \Cake\ORM\Association::MANY_TO_ONE:
                    case \Cake\ORM\Association::ONE_TO_ONE:
                        $config = ['title' => $title] + $relatedConfig;

                        $elements[] = [
                            'title' => __d('backend', 'Related {0}', $assoc->name()),
                            'render' => 'cell',
                            'cell' => 'Backend.EntityView',
                            'cellParams' => [ $relatedEntity ],
                            'cellOptions' => $config,
                            'cellTemplate' => 'table'
                        ];
                        break;

                    case \Cake\ORM\Association::ONE_TO_MANY:
                        $dataTable = array_merge([
                            'model' => $assoc->target(),
                            'data' => $relatedEntity,
                            'fieldsBlacklist' => [$assoc->foreignKey()],
                            'filter' => false,
                            'actions' => false,
                            'rowActions' => false,
                        ], $relatedConfig);

                        $elements[] = [
                            'title' => __d('backend', 'Related {0}', $assoc->name()),
                            'render' => 'table',
                            'tableOptions' => $dataTable
                        ];
                        break;

                    case \Cake\ORM\Association::MANY_TO_MANY:
                    default:
                        //$html = __d('backend', 'Association type not implemented {0}', $assoc->type());
                        break;
                }
            }
        }

        $this->set(compact('elements'));
    }
}
