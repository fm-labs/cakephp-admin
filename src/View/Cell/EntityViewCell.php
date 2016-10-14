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
    protected $_validCellOptions = ['model', 'fields', 'exclude', 'title', 'helpers', 'debug'];


    public $model;

    public $fields = [];

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
        $this->title = ($this->title === null) ? sprintf("%s #%s", Inflector::singularize(pluginSplit($this->model)[1]), $entity->id) : $this->title;

        $defaultField = ['title' => null, 'formatter' => null, 'formatterArgs' => []];
        $fields = [];
        foreach ($this->fields as $field => $config) {
            if (is_numeric($field)) {
                $field = $config;
                $config = $defaultField;
            }

            $fields[$field] = $config;
        }
        $schema = $this->_getTable()->schema();
        $associations = $this->_getTable()->associations();

        $data = [];
        foreach ($entity->visibleProperties() as $property):

            if (!isset($fields[$property]) && $this->exclude === '*') continue;
            if (is_array($this->exclude) && in_array($property, $this->exclude)) continue;

            $field = (isset($fields[$property])) ? $fields[$property] : $defaultField;
            $fieldLabel = ($field['title']) ?: Inflector::humanize($property);

            $val = $entity->get($property);

            $formatter = ($field['formatter']) ?: null;
            $formatterArgs = ($field['formatterArgs']) ?: [];

            $assoc = $associations->getByProperty($field);
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
                'field' => $field,
                'label' => $fieldLabel,
                'formatter' => $formatter,
                'formatterArgs' => $formatterArgs,
                'value' => $val,
                'assoc' => $assoc
            ];
        endforeach;

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
