<?php
declare(strict_types=1);

namespace Admin\Controller;

use Cake\Core\Exception\Exception;

/**
 * Class EntityInfoActionTrait
 *
 * @package Admin\Controller
 */
trait EntityInfoActionTrait
{
    public function info($entityId = null)
    {
        if (!$this->modelClass) {
            throw new Exception('Can not load entity of unknown model');
        }

        $modelClass = $this->modelClass;
        $Model = $this->loadModel($modelClass);
        $entity = $Model->get($entityId);

        if (method_exists($this, 'viewBuilder')) {
            $this->viewBuilder()
                ->plugin('Admin')
                ->templatePath('Admin/Entity')
                ->setTemplate('view');
        }

        $this->set(compact('modelClass', 'entityId', 'entity'));
        $this->render('Admin.view');
    }
}
