<?php
declare(strict_types=1);

namespace Admin\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventManager;
use Cake\Http\Response;
use Cake\Http\ServerRequest as Request;
use Cake\Utility\Inflector;
use Cake\View\Cell;

/**
 * EntityView cell
 */
class EntityViewCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected array $_validCellOptions = ['modelClass', 'fields', 'helpers'];

    /**
     * @deprecated
     */
    public ?string $modelClass;

    public array $fields = [];

    public array $helpers = [];

    public ?string $defaultTable = null;

    /**
     * @inheritDoc
     */
    public function __construct(
        ?Request $request = null,
        ?Response $response = null,
        ?EventManager $eventManager = null,
        array $cellOptions = [],
    ) {
        parent::__construct($request, $response, $eventManager, $cellOptions);

        if ($this->modelClass && !$this->defaultTable) {
            $this->defaultTable = $this->modelClass;
        }

        //$Table = $this->loadModel();
        $Table = $this->fetchTable($this->defaultTable ?? $this->modelClass);
        if (empty($this->fields)) {
            $this->fields = $Table->getSchema()->columns();
        }
    }

    /**
     * Default display method.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity object
     * @return void
     */
    public function display(EntityInterface $entity): void
    {
        //$Table = $this->loadModel();
        $Table = $this->fetchTable();

//        if ($this->title === null && $Table) {
//            $displayField = $Table->getDisplayField();
//            $this->title = $entity->get($displayField);
//        }
//        if (!$this->title === null) {
//            $this->title = sprintf("%s #%s", Inflector::singularize(pluginSplit($this->model)[1]), $entity->id);
//        }

        $defaultField = ['label' => null, 'class' => null, 'formatter' => null, 'formatterArgs' => []];
        $fields = [];
        foreach ($this->fields as $field => $config) {
            if (is_numeric($field)) {
                $field = $config;
                $config = $defaultField;
            }
            $fields[$field] = $config + $defaultField;
        }

        if ($Table) {
            $schema = $Table->getSchema();
            $associations = $Table->associations();
        } else {
            $schema = $associations = null;
        }

        $data = [];
        //$properties = $entity->getVisible();
        //$virtualProperties = $entity->getVirtual();

        $belongsTo = [];
        if ($associations) {
            foreach ($associations as $assoc) {
                if ($assoc->type() == 'manyToOne') {
                    $belongsTo[$assoc->getForeignKey()] = $assoc->getName();
                }
            }
        }

        $propDataFormatter = function ($property) use (&$data, $entity, $fields, $associations, $schema, $belongsTo) {

            $field = $fields[$property];
            $fieldLabel = $field['label'] ?: Inflector::humanize($property);
            $isVirtual = in_array($property, $entity->getVirtual());

            $val = $entity->get($property);

            $formatter = $field['formatter'] ?: null;
            $formatterArgs = $field['formatterArgs'] ?: [];

            $assoc = null;

            if (isset($belongsTo[$property])) {
                $assoc = $associations->get($belongsTo[$property]);
                //debug("$property belongsTo " . $belongsTo[$property] . " -> " . $assoc->getProperty());
                if ($entity->get($assoc->getProperty())) {
                //    $val = sprintf("%s (%s)", $val, $entity->get($assoc->getProperty())->get($assoc->getTarget()->getDisplayField()));
                //    $formatter = ['related', $assoc->getTarget()->getDisplayField()];

                    $related = $entity->get($assoc->getProperty());
                    $formatter = function ($val, $row, $args, $view) use ($related, $assoc) {
                        [$plugin, $modelName] = pluginSplit($assoc->getTarget()->getRegistryAlias());

                        return $view->Html->link(
                            $related->get($assoc->getTarget()->getDisplayField()),
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->getName(), 'action' => 'view', $related->id],
                            ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $related->get($assoc->getTarget()->getDisplayField())],
                        );
                    };
                } elseif ($entity->get($property)) {
                    $formatter = function ($val, $row, $args, $view) use ($assoc) {
                        [$plugin, $modelName] = pluginSplit($assoc->getTarget()->getRegistryAlias());

                        return $view->Html->link(
                            (string)$val,
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->getName(), 'action' => 'view', $val],
                            ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $val],
                        );
                    };
                } else {
                    $formatter = function () {
                        return __d('admin', 'Not assigned');
                    };
                }
            } elseif ($associations) {
                $assoc = $associations->getByProperty($property);
                if ($assoc) {
                    $assocType = $assoc->type();
                    //debug($property . "->" . $assocType);
                    switch ($assocType) {
                        case 'oneToMany':
                        case 'manyToMany':
                            //debug($assocType . ":" . $formatter);
                            //$formatter = "array";
                            /*
                            $formatter = function($val) use ($assoc) {
                                debug($val);
                                //return sprintf("%d %s", count($val), $assoc->getName());
                                return __d('admin', "{0} records", count($val));
                            };
                            */
                            $formatter = function ($val, $row, $args, $view) use ($assoc) {
                                if (!$val) {
                                    return '-';
                                }

                                return $view->Html->link(
                                    __d('admin', '{0} records', count($val)),
                                    [
                                        'controller' => $assoc->getName(),
                                        'action' => 'index',
                                        '?' => [
                                            '_filter' => [
                                                $assoc->getForeignKey() => $row->get($assoc->getTarget()->getPrimaryKey()),
                                            ],
                                        ],
                                    ],
                                    [
                                        'data-modal-frame',
                                        'data-modal-class' => 'modal-wide',
                                        'data-modal-title' => __d('admin', 'Related {0}', $assoc->getName()),
                                    ],
                                );
                            };
                            break;

                        case 'manyToOne':
                        case 'oneToOne':
                            $formatter = function ($val, $row, $args, $view) use ($assoc) {
                                if (!$val) {
                                    return $val;
                                }

                                return $view->Html->link(
                                    $val->get($assoc->getTarget()->getDisplayField()),
                                    ['controller' => $assoc->getName(), 'action' => 'view', $val->get($assoc->getTarget()->getPrimaryKey())],
                                    ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $val->get($assoc->getTarget()->getDisplayField())],
                                );
                            };
                            break;

                        default:
                            $formatter = $assocType;
                            debug($assocType . ':' . $formatter);
                            break;
                    }
                } else {
                    $column = $schema->getColumn($property);
                    $type = $column ? $column['type'] : gettype($val); // fallback to data type
                    $formatter = $formatter ?: $type; // fallback to column type
                }
            }

            $data[$property] = [
                'name' => $property,
                'label' => $fieldLabel,
                'formatter' => $formatter,
                'formatterArgs' => $formatterArgs,
                'value' => $val,
                'assoc' => $assoc,
                'class' => $field['class'],
                'virtual' => $isVirtual,
            ];

            return true;
        };

        $fieldNames = array_keys($fields);
        array_walk($fieldNames, $propDataFormatter);

        $this->set('model', $this->modelClass);
        $this->set('entity', $entity);
        $this->set('associations', $associations);
        $this->set('schema', $schema);
        $this->set('data', $data);
    }
}
