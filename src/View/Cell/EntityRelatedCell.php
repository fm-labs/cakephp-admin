<?php
declare(strict_types=1);

namespace Admin\View\Cell;

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
     * @param \Cake\Datasource\EntityInterface $entity Entity object
     * @return void
     */
    public function display($entity)
    {
        /** @var \Cake\ORM\Table $Model */
        $Model = $this->loadModel($this->modelClass);

        $elements = [];
        foreach ($this->related as $alias => $relatedConfig) {
            if (is_numeric($alias)) {
                $alias = $relatedConfig;
                $relatedConfig = [];
            }
            $assoc = $Model->getAssociation($alias);
            if ($assoc) {
                if ($entity->get($assoc->getProperty()) === null) {
                    //debug($assoc->type() . ": associated property not set: " . $assoc->getProperty());

                    $elements[] = [
                        'title' => __d('admin', 'Related {0}', $assoc->getName()),
                        'render' => 'content',
                        'content' => sprintf("Associated property not set: %s (%s)", $assoc->getProperty(), $assoc->type()),
                    ];
                    continue;
                }

                $relatedEntity = $entity->get($assoc->getProperty());
                $title = __d('admin', 'Related {0}', $assoc->getName());

                switch ($assoc->type()) {
                    case \Cake\ORM\Association::MANY_TO_ONE:
                    case \Cake\ORM\Association::ONE_TO_ONE:
                        $config = ['title' => $title] + $relatedConfig;

                        $elements[] = [
                            'title' => __d('admin', 'Related {0}', $assoc->getName()),
                            'render' => 'cell',
                            'cell' => 'Admin.EntityView',
                            'cellParams' => [ $relatedEntity ],
                            'cellOptions' => $config,
                            'cellTemplate' => 'table',
                        ];
                        break;

                    case \Cake\ORM\Association::ONE_TO_MANY:
                        $dataTable = array_merge([
                            'model' => $assoc->getTarget(),
                            'data' => $relatedEntity,
                            'fieldsBlacklist' => [$assoc->getForeignKey()],
                            'filter' => false,
                            'actions' => false,
                            'rowActions' => false,
                        ], $relatedConfig);

                        $elements[] = [
                            'title' => __d('admin', 'Related {0}', $assoc->getName()),
                            'render' => 'table',
                            'tableOptions' => $dataTable,
                        ];
                        break;

                    case \Cake\ORM\Association::MANY_TO_MANY:
                    default:
                        //$html = __d('admin', 'Association type not implemented {0}', $assoc->type());
                        break;
                }
            }
        }

        $this->set(compact('elements'));
    }
}
