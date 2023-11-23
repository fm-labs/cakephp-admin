<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Association;

class ViewAction extends BaseEntityAction implements EventListenerInterface
{
    protected $scope = ['table', 'form'];

    protected $_defaultConfig = [
        'modelClass' => null,
        'modelId' => null,
        'label' => null,
        'entity' => null,
        'entityOptions' => [],
        'fields' => [],
        'include' => [],
        'exclude' => [],
        'contain' => [],
        'related' => [],
        'actions' => [],
        'tabs' => [],
    ];

    protected $_defaultAttrs = ['data-icon' => 'file-o'];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->getConfig('label', __d('admin', 'View'));
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        $attrs = $this->getConfig('attrs', []);
        return array_merge($this->_defaultAttrs, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function _execute(Controller $controller)
    {
        if (!isset($this->_config['related'])) {
            $related = [];
            foreach ($this->model()->associations() as $assoc) {
                /** @var \Cake\ORM\Association $assoc */
                //debug($assoc->getAlias() . " : " . $assoc->type());
                switch ($assoc->type()) {
                    case Association::ONE_TO_MANY:
                    default:
                        $related[] = $assoc->getAlias();
                }
            }
            $this->set('related', $related);
        }

        $entity = $this->entity();
        if (!$entity) {
            throw new BadRequestException('ViewAction: Entity not found');
        }

        // entity view vars
        $controller->set('modelClass', $this->_config['modelClass']);
        $controller->set('entity', $entity);

        //$viewOptions['debug'] = Configure::read('debug');
        //$viewOptions['title'] = $entity->get($this->model()->getDisplayField());
        $viewOptions['modelClass'] = $this->_config['modelClass'];
        $viewOptions['fields'] = $this->_config['fields'];
        //$viewOptions['whitelist'] = $this->_config['include'];
        //$viewOptions['blacklist'] = $this->_config['exclude'];
        $controller->set('viewOptions', $viewOptions);

        $controller->set('actions', $this->_config['actions']);
        $controller->set('tabs', $this->_config['tabs']);

        $controller->set('title', $entity->get($this->model()->getDisplayField()));
        $controller->set('_serialize', ['entity']);
    }

    public function beforeRender(Event $event)
    {
        $entity = $event->getSubject()->viewVars['entity'];
        $modelClass = $event->getSubject()->viewVars['modelClass'];

        $event->getSubject()->viewVars['tabs']['data'] = [
            'title' => __d('admin', 'Data'),
            'url' => ['plugin' => 'Admin', 'controller' => 'Entity', 'action' => 'view', $modelClass, $entity->id],
        ];
    }

    public function implementedEvents(): array
    {
        return [
            'Controller.beforeRender' => 'beforeRender'
        ];
    }
}
