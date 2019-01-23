<?php

namespace Backend\Action;
use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class EditAction
 *
 * @package Backend\Action
 */
class EditAction extends BaseEntityAction
{
    protected $_scope = ['table', 'form'];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => [],
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => [],
        'fieldsets' => [],
    ];

    //public $noTemplate = true;

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend','Edit');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'edit'];
    }

    protected function _execute(Controller $controller)
    {
        if (isset($controller->viewVars['_entity']) && isset($controller->viewVars[$controller->viewVars['_entity']])) {
            $entity = $controller->viewVars[$controller->viewVars['_entity']];
        } else {
            $entity = $this->entity();
        }

        $_fields = $this->model()->schema()->columns();
        if (isset($controller->viewVars['fields'])) {
            $_fields = array_merge($_fields, $controller->viewVars['fields']);
        }

        if (isset($controller->viewVars['fields.access'])) {
            $entity->accessible($controller->viewVars['fields.access']);
        }

        $fields = $blacklist = $whitelist = [];
        if (isset($controller->viewVars['fields.blacklist'])) {
            $blacklist = $controller->viewVars['fields.blacklist'];
            $entity->accessible($blacklist, false);
        }
        if (isset($controller->viewVars['fields.whitelist'])) {
            $whitelist = $controller->viewVars['fields.whitelist'];
            $entity->accessible($whitelist, true);
        }
        foreach ($_fields as $_f => $field) {
            if (is_numeric($_f)) {
                $_f = $field;
                $field = [];
            }
            if (in_array($_f, $blacklist)) $field = false;
            if (!empty($whitelist) && !in_array($_f, $whitelist)) $field = false;

            $fields[$_f] = $field;
        }

        if ($this->_request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->_request->data);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__d('backend','Saved!'));
                $this->_redirect(['action' => $this->getAlias(), $entity->id] + $controller->request->query);
            } else {
                $this->_flashError();
            }
        }

        $controller->set('fields', $fields);
        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->modelClass);

        // associated
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->foreignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));

                    $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
                    $controller->set($var, $list);
                }
            } elseif ($assoc->type() == Association::ONE_TO_MANY) {
                //$var = Inflector::pluralize($assoc->property());
                $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
                $controller->set($assoc->foreignKey(), $list);
            } elseif ($assoc->type() == Association::ONE_TO_ONE) {
                //$list = ['foo' => 'bar'];
                //debug($assoc);
                //$controller->set($assoc->foreignKey(), $list);
                //debug($assoc->type());
            } elseif ($assoc->type() == Association::MANY_TO_MANY) {
                $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
                $controller->set(Inflector::variable($assoc->property()), $list);
            } else {
                //debug($assoc->type());
            }
        }

        /*
        foreach ($controller->Action->listActions() as $actionName) {
            $action = $controller->Action->getAction($actionName);
            if ($action instanceof EntityActionInterface && $action->hasForm()) {
                $tabs = (isset($controller->viewVars['tabs'])) ? $controller->viewVars['tabs'] : [];
                $tabs[$actionName] = ['title' => $action->getLabel(), 'url' => ['action' => $actionName, $controller->viewVars['entity']->id]];
                $controller->set('tabs', $tabs);
            }
        }
        */

        // config
        //$controller->set($this->_config);
    }
}
