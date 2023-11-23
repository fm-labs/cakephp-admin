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
        'fields' => [],
        'include' => [],
        'exclude' => [],
        'allowAccess' => [],
        'actions' => [],
        'rowActions' => [],
        'validator' => 'default',
        'redirectUrl' => true,
    ];

    public $scope = ['index'];

    //public $template = 'Admin.add';

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Add');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'plus'];
    }

    /**
     * @param array $inputs
     * @return array
     */
    protected function _normalizeInputs(array $inputs = []): array
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
     * @param \Cake\Controller\Controller $controller
     * @return \Cake\Http\Response|void|null
     */
    public function execute(Controller $controller)
    {
        #debug($this->_config);
        // @todo Remove legacy settings
        if (isset($this->_config['fields.whitelist'])) {
            $this->_config['include'] = $this->_config['fields.whitelist'];
            unset($this->_config['fields.whitelist']);
        }
        if (isset($this->_config['fields.blacklist'])) {
            $this->_config['exclude'] = $this->_config['fields.blacklist'];
            unset($this->_config['fields.blacklist']);
        }
        if (isset($this->_config['fields.access'])) {
            $this->_config['allowAccess'] = $this->_config['fields.access'];
            unset($this->_config['fields.access']);
        }

        // load helpers
        if (isset($this->_config['helpers'])) {
            $controller->viewBuilder()->addHelpers($this->_config['helpers']);
        }

        // custom template
        if (isset($this->_config['template'])) {
            $this->template = $this->_config['template'];
        }

        $entity = $this->_config['entity'] ?? $this->model()->newEmptyEntity();

        $fields = $this->model()->getSchema()->columns();
        if (isset($this->_config['fields'])) {
            $fields = array_merge($this->_config['fields'], $fields);
        }
        $fields = $this->_normalizeInputs($fields);

        // whitelist
        if (!empty($this->_config['include'])) {
            $fields = array_filter($fields, function ($key) {
                return in_array($key, $this->_config['include']);
            }, ARRAY_FILTER_USE_KEY);

            $entity->setAccess($this->_config['include'], true);
        }

        // blacklist
        if (!empty($this->_config['exclude'])) {
            $fields = array_filter($fields, function ($key) {
                return !in_array($key, $this->_config['exclude']);
            }, ARRAY_FILTER_USE_KEY);

            $entity->setAccess($this->_config['exclude'], false);
        }

        // allow field access
        if (isset($this->_config['allowAccess'])) {
            $entity->setAccess($this->_config['allowAccess'], true);
        }

        // process data submission
        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity(
                $entity,
                $this->request->getData(),
                ['validate' => $this->_config['validator']]
            );
            if ($this->model()->save($entity)) {
                $this->flashSuccess(__d('admin', 'Record created'));

                // redirect after add
                if ($this->_config['redirectUrl']) {
                    $redirectUrl = $this->_config['redirectUrl'] === true
                        ? ['action' => 'edit', $entity->id]
                        : $this->_config['redirectUrl'];
                    return $this->redirect($redirectUrl);
                }
            } else {
                debug($entity->getErrors());
                $this->flashError();
            }
        }

        // associated
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $fKey = $assoc->getForeignKey();
                if (strrpos($fKey, '_id') !== false) {
                    $var = substr($fKey, 0, strrpos($fKey, '_id'));
                    $var = lcfirst(Inflector::camelize(Inflector::pluralize($var)));
                    if (!isset($this->_config[$var])) {
                        $list = $assoc->getTarget()
                            ->find('list')
                            ->order([$assoc->getTarget()->getDisplayField() => 'ASC'])
                            ->toArray();
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
            //} else {
                //debug($assoc->type());
            }
        }

        // config
        //$controller->set($this->_config);

        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->loadModel()->getRegistryAlias());
        $controller->set('fields', $fields);
    }
}
