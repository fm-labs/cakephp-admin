<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/22/17
 * Time: 7:41 PM
 */

namespace Backend\Controller;


use Cake\Core\Exception\Exception;
use Cake\View\ViewVarsTrait;

trait EntityInfoActionTrait
{
    public function info($entityId = null)
    {
        if (!$this->modelClass) {
            throw new Exception("Can not load entity of unknown model");
        }

        $modelClass = $this->modelClass;
        $Model = $this->loadModel($modelClass);
        $entity = $Model->get($entityId);

        if (method_exists($this, 'viewBuilder')) {
            $this->viewBuilder()
                ->plugin('Backend')
                ->templatePath('Admin/Entity')
                ->template('view');
        }

        $this->set(compact('modelClass', 'entityId', 'entity'));
        $this->render('Backend.view');
    }
}