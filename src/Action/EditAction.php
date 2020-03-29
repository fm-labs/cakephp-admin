<?php
declare(strict_types=1);

namespace Backend\Action;

use Cake\Controller\Controller;
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
        'model.validator' => 'default',
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
        $viewVars = $controller->viewBuilder()->getVars();

        $_fields = $this->model()->getSchema()->columns();
        if (isset($viewVars['fields'])) {
            $_fields = array_merge($_fields, $viewVars['fields']);
        }

        if (isset($viewVars['fields.access'])) {
            $entity->setAccess($viewVars['fields.access'], true);
        }

        $fields = $blacklist = $whitelist = [];
        if (isset($viewVars['fields.whitelist'])) {
            $whitelist = $viewVars['fields.whitelist'];
            $entity->setAccess($whitelist, true);
        } else {
            $entity->setAccess('*', true);
        }
        if (isset($viewVars['fields.blacklist'])) {
            $blacklist = $viewVars['fields.blacklist'];
            $entity->setAccess($blacklist, false);
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
                $column = $this->model()->getSchema()->getColumn($_f);
                if ($column && isset($column['comment'])) {
                    $field['help'] = $column['comment'];
                }
            }

            $fields[$_f] = $field;
        }

        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->request->getData(), ['validate' => $this->_config['model.validator']]);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__d('backend', 'Saved!'));

                return $this->redirect([$entity->id] + $controller->getRequest()->getQuery());
            } else {
                $this->_flashError();
            }
        }

        $request = $controller->getRequest();
        $formId = join('-', array_map(
            ['Cake\Utility\Inflector', 'dasherize'],
            array_map(
                ['Cake\Utility\Inflector', 'underscore'],
                ['form', $request->getParam('controller'), $request->getParam('action')]
            )
        ));
        $formOptions = array_merge([
            'horizontal' => true,
            'id' => $formId,
        ], $this->_config['form.options']);
        $controller->set('form.options', $formOptions);
        $controller->set('fields', $fields);
        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->loadModel()->getRegistryAlias());

        // associated
        /** @var \Cake\ORM\Association $assoc */
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->getForeignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));

                    $list = $assoc->getTarget()->find('list')->order([$assoc->getTarget()->getDisplayField() => 'ASC'])->toArray();
                    $controller->set($var, $list);
                }
            } elseif ($assoc->type() == Association::ONE_TO_MANY) {
                // fetch list for 1-m relationships only if the property is set in entity
                if ($entity->get($assoc->getProperty())) {
                    $list = $assoc->getTarget()->find('list')->order([$assoc->getTarget()->getDisplayField() => 'ASC'])->toArray();
                    $controller->set(Inflector::variable($assoc->getProperty()), $list);
                    //debug("assoc list for " . $assoc->getName() . ": " . count($list)
                    //    . " item using key " . $assoc->foreignKey()
                    //    . " prop: " . $assoc->getProperty() . " -> " . Inflector::variable($assoc->getProperty()));
                }
            } elseif ($assoc->type() == Association::ONE_TO_ONE) {
                //$list = ['foo' => 'bar'];
                //debug($assoc);
                //$controller->set($assoc->foreignKey(), $list);
                //debug($assoc->type());
            } elseif ($assoc->type() == Association::MANY_TO_MANY) {
                $list = $assoc->getTarget()->find('list')->order([$assoc->getTarget()->getDisplayField() => 'ASC'])->toArray();
                $controller->set(Inflector::variable($assoc->getProperty()), $list);
            } else {
                //debug($assoc->type());
            }
        }
    }
}
