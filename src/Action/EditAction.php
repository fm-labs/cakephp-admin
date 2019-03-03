<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class EditAction
 *
 * @package Backend\Action
 */
class EditAction extends BaseEntityAction
{
    public $scope = ['table', 'form'];

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
        'form.options' => [],
        'model.validator' => 'default'
    ];

    //public $noTemplate = true;

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Edit');
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
        $entity = $this->entity();

        $_fields = $this->model()->schema()->columns();
        if (isset($controller->viewVars['fields'])) {
            $_fields = array_merge($_fields, $controller->viewVars['fields']);
        }

        if (isset($controller->viewVars['fields.access'])) {
            $entity->accessible($controller->viewVars['fields.access']);
        }

        $fields = $blacklist = $whitelist = [];
        if (isset($controller->viewVars['fields.whitelist'])) {
            $whitelist = $controller->viewVars['fields.whitelist'];
            $entity->accessible($whitelist, true);
        } else {
            $entity->accessible('*', true);
        }
        if (isset($controller->viewVars['fields.blacklist'])) {
            $blacklist = $controller->viewVars['fields.blacklist'];
            $entity->accessible($blacklist, false);
        }
        foreach ($_fields as $_f => $field) {
            if (is_numeric($_f)) {
                $_f = $field;
                $field = [];
            }
            if (in_array($_f, $blacklist)) {
                $field = false;
            }
            if (!empty($whitelist) && !in_array($_f, $whitelist)) {
                $field = false;
            }
            if ($_f == 'created' || $_f == 'modified') {
                $field = false;
            }

            // get help text from column comment
            if ($field && !isset($field['help'])) {
                $column = $this->model()->schema()->column($_f);
                if ($column && isset($column['comment'])) {
                    $field['help'] = $column['comment'];
                }
            }

            $fields[$_f] = $field;
        }

        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->request->data, ['validate' => $this->_config['model.validator']]);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__d('backend', 'Saved!'));
                $this->redirect([$entity->id] + $controller->request->query);
            } else {
                $this->_flashError();
            }
        }

        $request = $controller->request;
        $formId = join('-', array_map(
            ['Cake\Utility\Inflector', 'dasherize'],
            array_map(
                ['Cake\Utility\Inflector', 'underscore'],
                ['form', $request->params['controller'], $request->params['action']]
            )
        ));
        $formOptions = array_merge([
            'horizontal' => true,
            'id' => $formId
        ], $this->_config['form.options']);
        $controller->set('form.options', $formOptions);
        $controller->set('fields', $fields);
        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->modelClass);

        // associated
        /* @var Association $assoc */
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
                // fetch list for 1-m relationships only if the property is set in entity
                if ($entity->get($assoc->property())) {
                    $list = $assoc->target()->find('list')->order([$assoc->target()->displayField() => 'ASC'])->toArray();
                    $controller->set(Inflector::variable($assoc->property()), $list);
                    //debug("assoc list for " . $assoc->name() . ": " . count($list)
                    //    . " item using key " . $assoc->foreignKey()
                    //    . " prop: " . $assoc->property() . " -> " . Inflector::variable($assoc->property()));
                }
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
    }
}
