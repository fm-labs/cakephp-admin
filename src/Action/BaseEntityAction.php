<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\EntityActionInterface;
use Admin\Action\Traits\EntityActionFilterTrait;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

abstract class BaseEntityAction extends BaseAction implements EntityActionInterface
{
    use EntityActionFilterTrait;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
    ];

    /**
     * @var \Cake\Datasource\EntityInterface
     */
    protected $_entity;

    /**
     * @var array List of enabled scopes
     */
    protected $scope = ['table', 'form'];

    /**
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $config += ['filter' => null];

        if ($config['filter']) {
            $this->setFilter($config['filter']);
        }

        if (isset($config['scope'])) {
            $this->scope = $config['scope'];
        }
    }

    public function getUrl($id)
    {
        return ['action' => $this->action, $id];
    }

    /**
     * @inheritDoc
     */
    public function entity()
    {
        $controller =& $this->controller;

        if (!$this->_entity) {
            $entity = $controller->viewBuilder()->getVar('entity');
            if ($entity) {
                $this->_entity = $entity;
            } else {
                if (!$this->_config['modelId']) {
                    throw new \Exception(static::class . ' has no model ID defined');
                }
                $options = $this->_config['entityOptions'] ?? [];
                if (isset($this->_config['related'])) {
                    $options['contain'] = $this->_config['related'];
                }
                $this->_entity = $this->model()->get($this->_config['modelId'], $options);
            }
        }

        return $this->_entity;
    }

    /**
     * @inheritDoc
     */
    public function execute(Controller $controller)
    {
        parent::execute($controller);

        // detect model class and load entity
        if (!isset($this->_config['modelClass'])) {
            $this->_config['modelClass'] = $controller->loadModel()->getRegistryAlias();
        }
        if (!isset($this->_config['modelId'])) {
            $modelId = $controller->getRequest()->getParam('id');
            if (!$modelId && isset($controller->getRequest()->getParam('pass')[0])) {
                $modelId = $controller->getRequest()->getParam('pass')[0];
            }
            $this->_config['modelId'] = $modelId;
        }

        if (isset($this->_config['entity'])) {
            $this->_entity = $this->_config['entity'];
        }

        // custom template
        if (isset($this->_config['template'])) {
            $this->template = $this->_config['template'];
        }

        // breadcrumbs
        if (!isset($this->_config['breadcrumbs'])) {
            $breadcrumbs = [];
            if (
                $controller->getRequest()->getParam('plugin')
                && $controller->getRequest()->getParam('plugin') != $controller->getRequest()->getParam('controller')
            ) {
                $breadcrumbs[] = [
                    'title' => Inflector::humanize($controller->getRequest()->getParam('plugin')),
                    'url' => [
                        'plugin' => $controller->getRequest()->getParam('plugin'),
                        'controller' => $controller->getRequest()->getParam('plugin'),
                        'action' => 'index',
                    ],
                ];
                $breadcrumbs[] = [
                    'title' => Inflector::humanize($controller->getRequest()->getParam('controller')),
                    'url' => [
                        'plugin' => $controller->getRequest()->getParam('plugin'),
                        'controller' => $controller->getRequest()->getParam('controller'),
                        'action' => 'index',
                    ],
                ];
                $breadcrumbs[] = [
                    'title' => $this->entity()->get($this->model()->getDisplayField()),
                    'url' => [
                        'plugin' => $controller->getRequest()->getParam('plugin'),
                        'controller' => $controller->getRequest()->getParam('controller'),
                        'action' => 'view',
                        $this->entity()->get($this->model()->getPrimaryKey()),
                    ],
                ];
                $breadcrumbs[] = [
                    'title' => $this->getLabel(),
                ];
            }

            $controller->set('breadcrumbs', $breadcrumbs);
        }

        // i18n
        if ($this->model()->hasBehavior('Translate')) {
            $translation = $controller->getRequest()->getQuery('translation') ?: I18n::getLocale();
            $this->model()->setLocale($translation);
            $controller->set('translation', $translation);
            $controller->set('translations.languages', (array)Configure::read('Multilang.Locales'));
        }

        try {
            //$entity = $this->entity();
            //$controller->set('entity', $entity);

            // load helpers
            if (isset($this->_config['helpers'])) {
                $controller->viewBuilder()->setHelpers($this->_config['helpers'], true);
            }

            return $this->_execute($controller);
        } catch (\Cake\Core\Exception\Exception $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
            //$controller->redirect($controller->referer());
        }
    }

    /**
     * @param \Cake\Controller\Controller $controller Controller instance
     * @return null|void|\Cake\Http\Response
     */
    abstract protected function _execute(Controller $controller);

    /**
     * @param string $tokenStr String template
     * @param array $data Data
     * @return string
     */
    protected function _replaceTokens($tokenStr, $data = [])
    {
        if (is_array($tokenStr)) {
            foreach ($tokenStr as &$_tokenStr) {
                $_tokenStr = $this->_replaceTokens($_tokenStr, $data);
            }

            return $tokenStr;
        }

        // extract tokenized vars from data and cast them to their string representation
        preg_match_all('/:(\w+)/', $tokenStr, $matches);
        $inserts = array_intersect_key($data, array_flip(array_values($matches[1])));
        array_walk($inserts, function (&$val) {
            $val = (string)$val;
        });

        return Text::insert($tokenStr, $inserts);
    }
}
