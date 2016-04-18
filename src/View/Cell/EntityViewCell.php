<?php
namespace Backend\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
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
    protected $_validCellOptions = ['model', 'fields', 'exclude', 'title'];


    public $model;

    public $fields = [];

    public $exclude = [];

    public $title;

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(EntityInterface $entity)
    {
        $this->set('model', $this->model);
        $this->set('fields', $this->fields);
        $this->set('exclude', $this->exclude);
        $this->set('entity', $entity);

        $schema = $this->_getTable()->schema();
        $this->set('schema', $schema);

        $this->title = (isset($this->title)) ? $this->title : sprintf("%s #%s", $this->model, $entity->id);
        $this->set('title', $this->title);

    }

    /**
     * @return \Cake\ORM\Table
     */
    protected function _getTable()
    {
        return TableRegistry::get($this->model);
    }
}
