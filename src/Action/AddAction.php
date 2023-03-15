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

        // load helpers
        if (isset($this->_config['helpers'])) {
            $controller->viewBuilder()->addHelpers($this->_config['helpers']);
        }

        // custom template
        if (isset($this->_config['template'])) {
            $this->template = $this->_config['template'];
        }

        $entity = $this->_config['entity'] ?? $this->model()->newEmptyEntity();

        $_fields = $this->model()->getSchema()->columns();
        if (isset($this->_config['fields'])) {
            $_fields = array_merge($this->_config['fields'], $_fields);
        }
        $_fields = $this->_normalizeInputs($_fields);

        $fields = $blacklist = $whitelist = [];
        if (isset($this->_config['fields.whitelist'])) {
            $whitelist = $this->_config['fields.whitelist'];
        } else {
            $whitelist = array_keys($_fields);
        }
        if (isset($this->_config['fields.blacklist'])) {
            $blacklist = $this->_config['fields.blacklist'];
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

        // set access
        $entity->setAccess($whitelist, true);
        $entity->setAccess($blacklist, false);
        if (isset($this->_config['fields.access'])) {
            $entity->setAccess($this->_config['fields.access'], true);
        }

        // process data submission
        if ($this->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity(
                $entity,
                $this->request->getData(),
                ['validate' => $this->_config['model.validator']]
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
