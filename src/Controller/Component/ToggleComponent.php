<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\Table;

/**
 * Class ToggleComponent
 * @package Admin\Controller\Component
 */
class ToggleComponent extends Component
{
    /**
     * Default configuration
     * @var array
     */
    protected array $_defaultConfig = [
    ];

    /**
     * @param \Cake\ORM\Table $table
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
                'label' => $new === true ? 'Yes' : 'No',
                'class' => $new === true ? 'success' : 'danger',
                'result' => $table->save($entity) ? true : false,
            ];
        } else {
            $result = [
                'id' => $entity->id,
                'field' => $field,
                'result' => -1,
            ];
        }

        $controller = $this->getController();
        $controller->viewBuilder()->setClassName('Json');
        $controller->viewBuilder()->setLayout(false);
        $controller->set('result', $result);
        $controller->viewBuilder()->setOption('serialize', 'result');
    }
}
