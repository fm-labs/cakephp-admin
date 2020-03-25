<?php
namespace Backend\View\Cell;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventManager;
use Cake\Http\Response;
use Cake\Http\ServerRequest as Request;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
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
    protected $_validCellOptions = ['model', 'fields', 'whitelist', 'blacklist', 'title', 'helpers', 'debug'];

    public $model;

    public $fields = [];

    public $whitelist = [];

    public $blacklist = [];

    public $title;

    public $helpers = [];

    public $debug = false;

    /**
     * @var Table
     */
    protected $_table;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $cellOptions = []
    ) {
        parent::__construct($request, $response, $eventManager, $cellOptions);
    }

    /**
     * Default display method.
     *
     * @param EntityInterface $entity The entity object
     * @return void
     */
    public function display(EntityInterface $entity)
    {
        $Table = $this->_getTable();

        if ($this->title === null && $Table) {
            $displayField = $Table->getDisplayField();
            $this->title = $entity->get($displayField);
        }
        if (!$this->title === null) {
            $this->title = sprintf("%s #%s", Inflector::singularize(pluginSplit($this->model)[1]), $entity->id);
        }

        $defaultField = ['title' => null, 'class' => null, 'formatter' => null, 'formatterArgs' => []];
        $fields = [];
        foreach ($this->fields as $field => $config) {
            if (is_numeric($field)) {
                $field = $config;
                $config = $defaultField;
            }

            $fields[$field] = $config + $defaultField;
        }

        if ($this->whitelist === true) {
            $this->whitelist = array_keys($fields);
        }
        if (empty($this->whitelist)) {
            $this->whitelist = $entity->getVisible();
        }

        if ($Table) {
            $schema = $Table->getSchema();
            $associations = $Table->associations();
        } else {
            $schema = $associations = null;
        }

        $data = [];
        //$properties = $entity->getVisible();
        $virtualProperties = $entity->getVirtual();

        $belongsTo = [];
        if ($associations) {
            foreach ($associations as $assoc) {
                if ($assoc->type() == "manyToOne") {
                    $belongsTo[$assoc->getForeignKey()] = $assoc->getName();
                }
            }
        }

        $propDataFormatter = function ($property) use (&$data, $entity, $fields, $associations, $schema, $defaultField, $virtualProperties, $belongsTo) {

            if (!empty($this->whitelist) && !in_array($property, $this->whitelist)) {
                return false;
            }

            if (!empty($this->blacklist) && in_array($property, $this->blacklist)) {
                return false;
            }

            $field = (isset($fields[$property])) ? $fields[$property] : $defaultField;
            $fieldLabel = ($field['title']) ?: Inflector::humanize($property);
            $isVirtual = in_array($property, $virtualProperties);

            $val = $entity->get($property);

            $formatter = ($field['formatter']) ?: null;
            $formatterArgs = ($field['formatterArgs']) ?: [];

            $assoc = null;

            if (isset($belongsTo[$property])) {
                $assoc = $associations->get($belongsTo[$property]);
                //debug("$property belongsTo " . $belongsTo[$property] . " -> " . $assoc->getProperty());
                if ($entity->get($assoc->getProperty())) {
                //    $val = sprintf("%s (%s)", $val, $entity->get($assoc->getProperty())->get($assoc->getTarget()->getDisplayField()));
                //    $formatter = ['related', $assoc->getTarget()->getDisplayField()];

                    $related = $entity->get($assoc->getProperty());
                    $formatter = function ($val, $row, $args, $view) use ($related, $assoc) {
                        list($plugin, $modelName) = pluginSplit($assoc->getTarget()->getRegistryAlias());

                        return $view->Html->link(
                            $related->get($assoc->getTarget()->getDisplayField()),
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->getName(), 'action' => 'view', $related->id],
                            ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $related->get($assoc->getTarget()->getDisplayField())]
                        );
                    };
                } elseif ($entity->get($property)) {
                    $formatter = function ($val, $row, $args, $view) use ($assoc) {
                        list($plugin, $modelName) = pluginSplit($assoc->getTarget()->getRegistryAlias());

                        return $view->Html->link(
                            $val,
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->getName(), 'action' => 'view', $val],
                            ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $val]
                        );
                    };
                } else {
                    $formatter = function () {
                        return __d('backend', 'Not assigned');
                    };
                }
            } elseif ($associations) {
                $assoc = $associations->getByProperty($property);
                if ($assoc) {
                    $assocType = $assoc->type();
                    //debug($property . "->" . $assocType);
                    switch ($assocType) {
                        case "oneToMany":
                        case "manyToMany":
                            //debug($assocType . ":" . $formatter);
                            //$formatter = "array";
                            /*
                            $formatter = function($val) use ($assoc) {
                                debug($val);
                                //return sprintf("%d %s", count($val), $assoc->getName());
                                return __d('backend', "{0} records", count($val));
                            };
                            */
                            $formatter = function ($val, $row, $args, $view) use ($assoc) {
                                if (!$val) {
                                    return '-';
                                }

                                return $view->Html->link(
                                    __d('backend', "{0} records", count($val)),
                                    [
                                        'controller' => $assoc->getName(),
                                        'action' => 'index',
                                        '_filter' => [
                                            $assoc->getForeignKey() => $row->get($assoc->getTarget()->getPrimaryKey()),
                                        ],
                                    ],
                                    [
                                        'data-modal-frame',
                                        'data-modal-class' => 'modal-wide',
                                        'data-modal-title' => __d('backend', "Related {0}", $assoc->getName()),
                                    ]
                                );
                            };
                            break;

                        case "manyToOne":
                        case "oneToOne":
                            $formatter = function ($val, $row, $args, $view) use ($assoc) {
                                if (!$val) {
                                    return $val;
                                }

                                return $view->Html->link(
                                    $val->get($assoc->getTarget()->getDisplayField()),
                                    ['controller' => $assoc->getName(), 'action' => 'view', $val->get($assoc->getTarget()->getPrimaryKey())],
                                    ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $val->get($assoc->getTarget()->getDisplayField())]
                                );
                            };
                            break;

                        default:
                            $formatter = $assocType;
                            debug($assocType . ":" . $formatter);
                            break;
                    }
                } else {
                    $column = $schema->getColumn($property);
                    $type = ($column) ? $column['type'] : gettype($val); // fallback to data type
                    $formatter = ($formatter) ?: $type; // fallback to column type
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

        array_walk($this->whitelist, $propDataFormatter);

        $this->set('debug', $this->debug && Configure::read('debug'));
        $this->set('model', $this->model);
        $this->set('entity', $entity);
        $this->set('associations', $associations);
        $this->set('schema', $schema);
        $this->set('title', $this->title);
        $this->set('data', $data);
    }

    /**
     * @return \Cake\ORM\Table
     */
    protected function _getTable()
    {
        if (!$this->_table && $this->model) {
            $this->_table = TableRegistry::getTableLocator()->get($this->model);
        }

        return $this->_table;
    }
}
