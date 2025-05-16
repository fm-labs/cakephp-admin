<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;

/**
 * Class MediaAction
 *
 * @package Admin\Action
 */
class MediaAction extends BaseEntityAction
{
    protected array $scope = [];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Media');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'media'];
    }

    protected function _execute(Controller $controller): ?Response
    {
        $fields = [];

        if ($controller->modelClass) {
            $Model = $controller->loadModel($controller->modelClass);
            if ($Model->hasBehavior('Media')) {
                $fields = $Model->getMediaFields();
            }
        }

        //debug($fields);

        $controller->set('fields', $fields);
        $controller->set('modelClass', $controller->modelClass);
        $controller->set('entity', $this->entity());

        return null;
    }
}
