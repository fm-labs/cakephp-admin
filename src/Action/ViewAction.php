<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Network\Exception\BadRequestException;

class ViewAction extends BaseEntityAction
{
    protected $_defaultConfig = [
        'modelClass' => null,
        'modelId' => null,
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => [],
        'viewOptions' => [],
        'actions' => []
    ];

    public function _execute(Controller $controller)
    {
        // attempt to get model ID from request if not set
        if (!$this->_config['modelId']) {
            $this->_config['modelId'] = (isset($controller->request->params['id'])) ? $controller->request->params['id'] : null;
        }
        if (!$this->_config['modelId']) {
            $this->_config['modelId'] = (isset($controller->request->params['pass'][0])) ? $controller->request->params['pass'][0] : null;
        }

        if (!$this->_config['modelId']) {
            throw new BadRequestException('ViewAction: Model ID missing');
        }
        $entity = $this->model()->get($this->_config['modelId']);

        $this->_config['viewOptions']['model'] = $this->_config['modelClass'];
        $this->_config['viewOptions']['title'] = $entity->get($this->model()->displayField());
        $this->_config['viewOptions']['debug'] = Configure::read('debug');
        $this->_config['viewOptions']['fields'] = $this->_config['fields'];
        $this->_config['viewOptions']['whitelist'] = $this->_config['fields.whitelist'];
        $this->_config['viewOptions']['blacklist'] = $this->_config['fields.blacklist'];
        $controller->set('viewOptions', $this->_config['viewOptions']);

        $controller->set('entity', $entity);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('_serialize', ['entity']);
    }
}
