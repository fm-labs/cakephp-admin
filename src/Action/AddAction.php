<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class AddAction
 *
 * @package Admin\Action
 */
class AddAction extends BaseAction implements ActionInterface, IndexActionInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => [],
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => [],
        'model.validator' => 'default',
    ];

    public $scope = ['index'];

    //public $template = 'Admin.add';

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('admin', 'Add');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'plus'];
    }

    protected function _normalizeInputs(array $inputs = [])
    {
        $normalized = [];
        foreach ($inputs as $_f => $field) {
            if (is_numeric($_f)) {
                $_f = $field;
                $field = [];
            }

            if (!array_key_exists($_f, $normalized)) {
                $normalized[$_f] = $field;
            }
        }

        return $normalized;
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        $viewVars = $controller->viewBuilder()->getVars();
        
        if (isset($viewVars['_entity']) && isset($viewVars[$viewVars['_entity']])) {
            $entity = $viewVars[$viewVars['_entity']];
        } else {
            $entity = $this->model()->newEmptyEntity();
        }

        $_fields = $this->model()->getSchema()->columns();
        if (isset($viewVars['fields'])) {
            $_fields = array_merge($viewVars['fields'], $_fields);
        }
        $_fields = $this->_normalizeInputs($_fields);

        $fields = $blacklist = $whitelist = [];
        if (isset($viewVars['fields.whitelist'])) {
            $whitelist = $viewVars['fields.whitelist'];
        } else {
            $whitelist = array_keys($_fields);
        }
        if (isset($viewVars['fields.blacklist'])) {
            $blacklist = $viewVars['fields.blacklist'];
        }

        $fields['id'] = [];
        foreach ($_fields as $field => $config) {
            if (!empty($whitelist) && !in_array($field, $whitelist)) {
                continue;
            }

            if (!empty($blacklist) && in_array($field, $blacklist)) {
                continue;
            }

            $fields[$field] = $config;
        }

        $entity->setAccess($whitelist, true);
        $entity->setAccess($blacklist, false);
        if (isset($viewVars['fields.access'])) {
            $entity->setAccess($viewVars['fields.access'], true);
        }

        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->request->getData(), ['validate' => $this->_config['model.validator']]);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__d('admin', 'Record created'));
                $this->redirect(['action' => 'edit', $entity->id]);
            } else {
                debug($entity->getErrors());
                $this->_flashError();
            }
        }

        // associated
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->getForeignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));
                    if (!isset($viewVars[$var])) {
                        $list = $assoc->getTarget()->find('list')->order([$assoc->getTarget()->getDisplayField() => 'ASC'])->toArray();
                        $controller->set($var, $list);
                    }
                }
//            } elseif ($assoc->type() == Association::ONE_TO_MANY) {
//                $var = Inflector::pluralize($assoc->getProperty());
//                $list = $assoc->target()->find('list')->order([$assoc->target()->getDisplayField() => 'ASC'])->toArray();
//                $controller->set($assoc->foreignKey(), $list);
//            } elseif ($assoc->type() == Association::ONE_TO_ONE) {
//                //$list = ['foo' => 'bar'];
//                //debug($assoc);
//                //$controller->set($assoc->foreignKey(), $list);
//                //debug($assoc->type());
            } else {
                //debug($assoc->type());
            }
        }

        // config
        //$controller->set($this->_config);

        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->modelClass);
        $controller->set('fields', $fields);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = $viewVars[$key] ?? $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($viewVars['helpers'])) {
            $controller->viewBuilder()->setHelpers($viewVars['helpers'], true);
        }

        // custom template
        if (isset($viewVars['template'])) {
            $this->template = $viewVars['template'];
        }

        return $this->_execute($controller);
    }
}
