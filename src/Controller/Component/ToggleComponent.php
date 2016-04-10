<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/7/15
 * Time: 11:11 AM
 */

namespace Backend\Controller\Component;

use Cake\Controller\Component\FlashComponent as CakeFlashComponent;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

class ToggleComponent extends CakeFlashComponent
{
    /**
     * Default configuration
     * @var array
     */
    protected $_defaultConfig = [
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    public function toggleBoolean(Table $table, $id, $field)
    {
        $entity = $table->find()->select(['id', $field])->where(['id' => $id])->first();
        if ($entity) {

            $val = (bool) $entity->{$field};
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
