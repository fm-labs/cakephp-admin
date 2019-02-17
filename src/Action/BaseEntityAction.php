<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Traits\EntityActionFilterTrait;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\I18n\I18n;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

abstract class BaseEntityAction extends BaseAction implements EntityActionInterface
{
    use EntityActionFilterTrait;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => []
    ];

    /**
     * @var EntityInterface
     */
    protected $_entity;

    /**
     * @var array List of enabled scopes
     */
    protected $_scope = ['table', 'form'];

    /**
     * @var Table
     */
    protected $_model;

    public function hasForm()
    {
        return false;
    }

    public function __construct(Controller $controller, array $config = [])
    {
        parent::__construct($controller, $config);
        $config += ['filter' => null];

        if ($config['filter']) {
            $this->setFilter($config['filter']);
        }

        if (isset($config['scope'])) {
            $this->_scope = $config['scope'];
        }
    }

    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class and load entity
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }
        if (!isset($controller->viewVars['modelId'])) {
            $modelId = $controller->request->param('id');
            $modelId = ($modelId) ?: $controller->request->param('pass')[0]; // @TODO request param 'pass' might be empty or unset
            $this->_config['modelId'] = $modelId;
        }
        if (isset($controller->viewVars['entityOptions'])) {
            $this->_config['entityOptions'] = $controller->viewVars['entityOptions'];
        }
        if (isset($controller->viewVars['entity'])) {
            $this->_entity = $controller->viewVars['entity'];
        }

        // custom template
        if (isset($controller->viewVars['template'])) {
            $this->template = $controller->viewVars['template'];
        }

        // i18n
        $translation = I18n::locale();
        if ($this->model()->hasBehavior('Translate')) {
            $translation = ($controller->request->query('translation')) ?: I18n::locale();
            $this->model()->locale($translation);
            $controller->set('translation', $translation);
            $controller->set('translations.languages', (array)Configure::read('Multilang.Locales'));
        }

        try {
            //$entity = $this->entity();
            //$controller->set('entity', $entity);

            // load helpers
            if (isset($controller->viewVars['helpers'])) {
                $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
            }

            return $this->_execute($controller);
        } catch (\Cake\Core\Exception\Exception $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
            //$controller->redirect($controller->referer());
        }
    }

    public function model()
    {
        if (!$this->_model) {
            if (!$this->_config['modelClass']) {
                //throw new \Exception(get_class($this) . ' has no model class defined');
                return false;
            }

            $this->_model = TableRegistry::get($this->_config['modelClass']);
        }

        return $this->_model;
    }

    public function entity()
    {
        if (!$this->_entity) {
            if (!$this->_config['modelId']) {
                throw new \Exception(get_class($this) . ' has no model ID defined');
            }

            $options = (isset($this->_config['entityOptions'])) ? $this->_config['entityOptions'] : [];
            $this->_entity = $this->model()->get($this->_config['modelId'], $options);
        }

        return $this->_entity;
    }

    protected function _replaceTokens($tokenStr, $data = [])
    {
        if (is_array($tokenStr)) {
            foreach ($tokenStr as &$_tokenStr) {
                $_tokenStr = $this->_replaceTokens($_tokenStr, $data);
            }

            return $tokenStr;
        }

        // extract tokenized vars from data and cast them to their string representation
        preg_match_all('/\:(\w+)/', $tokenStr, $matches);
        $inserts = array_intersect_key($data, array_flip(array_values($matches[1])));
        array_walk($inserts, function (&$val, $key) {
            $val = (string)$val;
        });

        return Text::insert($tokenStr, $inserts);
    }

    public function beforeRender(Event $event)
    {

        /*
        debug("before Render");
        // actions
        $controller = $event->subject();
        $entity = $this->entity();

        if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Controller.buildEntityActions', [
            //    'entity' => $entity,
            //    'actions' => (array) $this->_config['actions']
            //]);
            //$this->_config['actions'] = (array)$event->data['actions'];

            foreach ($this->_config['actions'] as $idx => &$action) {
                list($title, $url, $attr) = $action;
                $url = $this->_replaceTokens($url, $entity->toArray());
                $action = [$title, $url, $attr];
            }

            $controller->set('actions', $this->_config['actions']);
        }
        */
    }
}
