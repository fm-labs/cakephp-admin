<?php
namespace Backend\View\Cell;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
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
    protected $_validCellOptions = ['model', 'fields', 'whitelist', 'blacklist', 'title', 'helpers', 'debug', 'exclude'];


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

    public $helpers = [];

    public $debug = false;

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

        if ($this->title === null) {
            $displayField = $this->_getTable()->displayField();
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
        $schema = $this->_getTable()->schema();
        $associations = $this->_getTable()->associations();

        $data = [];
        $properties = $entity->visibleProperties();
        $virtualProperties = $entity->virtualProperties();

        $propDataFormatter = function($property) use (&$data, $entity, $fields, $associations, $schema, $defaultField, $virtualProperties) {

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

            $assoc = $associations->getByProperty($property);
            if ($assoc) {
                $assocType = $assoc->type();
                switch ($assocType) {
                    case "oneToMany":
                    case "manyToMany":
                        $formatter = "array";
                        break;
                    case "manyToOne":
                        $formatter = "object";
                        break;#
                    default:
                        $formatter = $assocType;
                        break;

                }
            } else {
                $column = $schema->column($property);
                $type = ($column) ? $column['type'] : gettype($val); // fallback to data type
                $formatter = ($formatter) ?: $type; // fallback to column type
            }


            $data[] = [
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
        array_walk($properties, $propDataFormatter);

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
        return TableRegistry::get($this->model);
    }
}
