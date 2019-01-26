<?php
namespace Backend\View\Cell;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
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
    protected $_validCellOptions = ['model', 'fields', 'whitelist', 'blacklist', 'title', 'related', 'helpers', 'debug', 'exclude'];

    public $model;

    public $fields = [];

    public $whitelist = [];

    public $blacklist = [];

    /**
     * @var array
     * @deprecated Use blacklist instead
     */
    public $exclude = [];

    public $title;

    //public $related = [];

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
     * @return void
     */
    public function display(EntityInterface $entity)
    {
        // legacy support for 'exclude' property.
        if ($this->exclude === '*') {
        } elseif (!empty($this->exclude)) {
            $this->blacklist = $this->exclude;
        }
        $this->exclude = [];

        $Table = $this->_getTable();

        if ($this->title === null && $Table) {
            $displayField = $Table->displayField();
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
            $this->whitelist = $entity->visibleProperties();
        }

        if ($Table) {
            $schema = $Table->schema();
            $associations = $Table->associations();
        } else {
            $schema = $associations = null;
        }

        $data = [];
        //$properties = $entity->visibleProperties();
        $virtualProperties = $entity->virtualProperties();

        $belongsTo = [];
        if ($associations) {
            foreach ($associations as $assoc) {
                if ($assoc->type() == "manyToOne") {
                    $belongsTo[$assoc->foreignKey()] = $assoc->name();
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
                //debug("$property belongsTo " . $belongsTo[$property] . " -> " . $assoc->property());
                if ($entity->get($assoc->property())) {
                //    $val = sprintf("%s (%s)", $val, $entity->get($assoc->property())->get($assoc->target()->displayField()));
                //    $formatter = ['related', $assoc->target()->displayField()];

                    $related = $entity->get($assoc->property());
                    $formatter = function ($val, $row, $args, $view) use ($related, $assoc) {
                        list($plugin, $modelName) = pluginSplit($assoc->target()->registryAlias());
                        return $view->Html->link(
                            $related->get($assoc->target()->displayField()),
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->name(), 'action' => 'view', $related->id],
                            ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $related->get($assoc->target()->displayField())]
                        );
                    };
                } elseif ($entity->get($property)) {
                    $formatter = function ($val, $row, $args, $view) use ($assoc) {
                        list($plugin, $modelName) = pluginSplit($assoc->target()->registryAlias());
                        return $view->Html->link(
                            $val,
                            [/*'plugin' => $plugin,*/ 'controller' => $assoc->name(), 'action' => 'view', $val],
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
                                //return sprintf("%d %s", count($val), $assoc->name());
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
                                        'controller' => $assoc->name(),
                                        'action' => 'index',
                                        '_filter' => [
                                            $assoc->foreignKey() => $row->get($assoc->target()->primaryKey())
                                        ]
                                    ],
                                    [
                                        'data-modal-frame',
                                        'data-modal-class' => 'modal-wide',
                                        'data-modal-title' => __d('backend', "Related {0}", $assoc->name())
                                    ]
                                );
                            };
                            break;

                        case "manyToOne":
                        case "oneToOne":
                            //$formatter = ['related', $assoc->target()->displayField()];
                            $formatter = function ($val, $row, $args, $view) use ($assoc) {
                                if (!$val) {
                                    return $val;
                                }

                                return $view->Html->link(
                                    $val->get($assoc->target()->displayField()),
                                    ['controller' => $assoc->name(), 'action' => 'view', $val->get($assoc->target()->primaryKey())],
                                    ['data-modal-frame', 'data-modal-class' => 'modal-wide', 'data-modal-title' => $val->get($assoc->target()->displayField())]
                                );
                            };
                            break;

                        default:
                            $formatter = $assocType;
                            debug($assocType . ":" . $formatter);
                            break;
                    }
                } else {
                    $column = $schema->column($property);
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
                'virtual' => $isVirtual
            ];
        };

        array_walk($this->whitelist, $propDataFormatter);

        $this->set('debug', $this->debug && Configure::read('debug'));
        $this->set('model', $this->model);
        $this->set('entity', $entity);

//        $related = [];
//        foreach ($this->related as $_related => $_relatedConf) {
//            if (is_numeric($_related)) {
//                $_related = $_relatedConf;
//                $_relatedConf = [];
//            }
//            $related[$_related] = $_relatedConf;
//        }
//        $this->set('related', $related);

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
            $this->_table = TableRegistry::get($this->model);
        }

        return $this->_table;
    }
}
