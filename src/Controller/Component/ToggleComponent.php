<?php
namespace Backend\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\Table;

/**
 * Class ToggleComponent
 * @package Backend\Controller\Component
 */
class ToggleComponent extends Component
{
    /**
     * Default configuration
     * @var array
     */
    protected $_defaultConfig = [
    ];

    /**
     * @param Table $table
     * @param $id
     * @param $field
     */
    public function toggleBoolean(Table $table, $id, $field)
    {
        $entity = $table->find()->select(['id', $field])->where(['id' => $id])->first();
        if ($entity) {
            $val = (bool)$entity->{$field};
            $new = !$val;

            $entity->{$field} = $new;
            $result = [
                'id' => $entity->id,
                'field' => $field,
                'old' => $val,
                'new' => $new,
                'label' => ($new === true) ? 'Yes' : 'No',
                'class' => ($new === true) ? 'success' : 'danger',
                'result' => ($table->save($entity)) ? true : false,
            ];
        } else {
            $result = [
                'id' => $entity->id,
                'field' => $field,
                'result' => -1
            ];
        }

        $controller = $this->_registry->getController();
        $controller->viewBuilder()->className('Json');
        $controller->viewBuilder()->layout(false);
        $controller->set('result', $result);
        $controller->set('_serialize', 'result');
    }
}
