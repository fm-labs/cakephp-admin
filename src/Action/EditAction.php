<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class EditAction
 *
 * @package Admin\Action
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
        'redirectUrl' => true,
    ];

    //public $noTemplate = true;

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Edit');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'edit'];
    }

    /**
     * @param \Cake\Controller\Controller $controller Controller instance
     * @return \Cake\Http\Response|void|null
     * @throws \Exception
     */
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

            $column = $this->model()->getSchema()->getColumn($_f);
            // get help text from column comment
            if ($field && !isset($field['help'])) {
                if ($column && isset($column['comment'])) {
                    $field['help'] = $column['comment'];
                }
            }

            $fields[$_f] = $field;
        }

        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity(
                $entity,
                $this->request->getData(),
                ['validate' => $this->_config['model.validator']]
            );
            if ($this->model()->save($entity)) {
                $this->flashSuccess(__d('admin', 'Saved!'));

                $redirectUrl = $this->_config['redirectUrl'] === true
                    ? [$entity->id] + $controller->getRequest()->getQuery()
                    : $this->_config['redirectUrl'];
                $redirectUrl = $controller->referer($redirectUrl);
                //debug($redirectUrl);
                return $this->redirect($redirectUrl);
            } else {
                $this->flashError();
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

        // associated
        /** @var \Cake\ORM\Association $assoc */
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->getForeignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));
                    if (!$controller->viewBuilder()->getVar($var)) {
                        $list = $assoc->getTarget()
                            ->find('list')
                            ->order([$assoc->getTarget()->getDisplayField() => 'ASC'])
                            ->toArray();
                        $controller->set($var, $list);
                        //debug("assoc list for " . $assoc->getName() . ": " . count($list)
                        //    . " item using key " . $assoc->getForeignKey()
                        //    . " prop: " . $assoc->getProperty() . " -> " . Inflector::variable($assoc->getProperty()));
                    }
                }
            } elseif ($assoc->type() == Association::ONE_TO_MANY) {
                $var = Inflector::variable($assoc->getProperty());
                // fetch list for 1-m relationships only if the property is set in entity
                if ($entity->get($assoc->getProperty()) && !$controller->viewBuilder()->getVar($var)) {
                    $list = $assoc->getTarget()
                        ->find('list')
                        ->order([$assoc->getTarget()->getDisplayField() => 'ASC'])
                        ->toArray();
                    $controller->set($var, $list);
                    //debug("assoc list for " . $assoc->getName() . ": " . count($list)
                    //    . " item using key " . $assoc->getForeignKey()
                    //    . " prop: " . $assoc->getProperty() . " -> " . Inflector::variable($assoc->getProperty()));
                }
            //} elseif ($assoc->type() == Association::ONE_TO_ONE) {
                //$list = ['foo' => 'bar'];
                //debug($assoc);
                //$controller->set($assoc->foreignKey(), $list);
                //debug($assoc->type());
            } elseif ($assoc->type() == Association::MANY_TO_MANY) {
                $var = Inflector::variable($assoc->getProperty());
                if (!$controller->viewBuilder()->getVar($var)) {
                    $list = $assoc->getTarget()
                        ->find('list')
                        ->order([$assoc->getTarget()->getDisplayField() => 'ASC'])
                        ->toArray();
                    $controller->set($var, $list);
                }
            }
            //else {
                //debug($assoc->type());
            //}
        }

        $controller->set('form.options', $formOptions);
        $controller->set('fields', $fields);
        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->loadModel()->getRegistryAlias());
    }
}
