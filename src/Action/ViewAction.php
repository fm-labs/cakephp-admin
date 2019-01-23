<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\BadRequestException;

class ViewAction extends BaseEntityAction implements EventListenerInterface
{
    protected $_scope = ['table', 'form'];

    protected $_defaultConfig = [
        'entity' => null,
        'entityOptions' => [],
        'modelClass' => null,
        'modelId' => null,
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => [],
        'related' => [],
        'viewOptions' => [],
        'actions' => [],
        'tabs' => [],
    ];

    public $template = "Backend.view";

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend','Details');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'file-text'];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {

        $entity = $this->entity();
        if (!$entity) {
            if ($this->_config['entity'] !== null) {
                $entity = $this->_config['entity'];
                $this->_config['modelId'] = $entity->id;

            } else {

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
            }
        }


        $related = [];
        foreach ($this->_config['related'] as $_related => $_relatedConf) {
            if (is_numeric($_related)) {
                $_related = $_relatedConf;
                $_relatedConf = [];
            }
            $related[$_related] = $_relatedConf;
        }
        $this->_config['related'] = $related;

        $this->_config['viewOptions']['model'] = $this->_config['modelClass'];
        $this->_config['viewOptions']['title'] = $entity->get($this->model()->displayField());
        $this->_config['viewOptions']['debug'] = Configure::read('debug');
        $this->_config['viewOptions']['fields'] = $this->_config['fields'];
        $this->_config['viewOptions']['whitelist'] = $this->_config['fields.whitelist'];
        $this->_config['viewOptions']['blacklist'] = $this->_config['fields.blacklist'];
        //$this->_config['viewOptions']['related'] = $this->_config['related'];
        $controller->set('viewOptions', $this->_config['viewOptions']);
        $controller->set('title', $entity->get($this->model()->displayField()));
        $controller->set('modelClass', $this->_config['modelClass']);
        $controller->set('entity', $entity);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('tabs', $this->_config['tabs']);
        $controller->set('associations', $this->model()->associations());
        $controller->set('related', $this->_config['related']);
        $controller->set('_serialize', ['entity']);
        //$controller->render();
    }

    public function beforeRender(Event $event)
    {
        $entity = $event->subject()->viewVars['entity'];
        $modelClass = $event->subject()->viewVars['modelClass'];

        $event->subject()->viewVars['tabs']['data'] = [
            'title' => __d('backend','Data'),
            'url' => ['plugin' => 'Backend', 'controller' => 'Entity', 'action' => 'view', $modelClass, $entity->id]
        ];
    }

    public function implementedEvents()
    {
        return [
            //'Controller.beforeRender' => 'beforeRender'
        ];
    }
}
