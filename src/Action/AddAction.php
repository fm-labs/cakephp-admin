<?php

namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Network\Response;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class AddAction
 *
 * @package Backend\Action
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
        'fields.whitelist' => []
    ];


    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend','Add');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'plus'];
    }

    /**
     *
     */
    public function _execute(Controller $controller)
    {
        if (isset($controller->viewVars['_entity']) && isset($controller->viewVars[$controller->viewVars['_entity']])) {
            $entity = $controller->viewVars[$controller->viewVars['_entity']];
        } else {
            $entity = $this->model()->newEntity();
        }

        if ($this->_request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->_request->data);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__d('backend','Record created'));
                $this->_redirect(['action' => 'edit', $entity->id]);
            } else {
                debug($entity->errors());
                $this->_flashError();
            }
        }

        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->modelClass);

        // associated
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->foreignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));
                    if (!isset($controller->viewVars[$var])) {
                        $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
                        $controller->set($var, $list);
                    }
                }
//            } elseif ($assoc->type() == Association::ONE_TO_MANY) {
//                $var = Inflector::pluralize($assoc->property());
//                $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
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
        $controller->set($this->_config);
    }

    /**
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($controller->viewVars['helpers'])) {
            $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
        }

        // custom template
        if (isset($controller->viewVars['template'])) {
            $this->template = $controller->viewVars['template'];
        }

        return $this->_execute($controller);
    }
}
