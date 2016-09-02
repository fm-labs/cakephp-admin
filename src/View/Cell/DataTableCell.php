<?php
namespace Backend\View\Cell;


use Cake\Collection\CollectionInterface;
use Cake\ORM\TableRegistry;
use Cake\View\Cell;

class DataTableCell extends Cell
{
    public $modelClass;

    public $model;

    public function display($params = [])
    {
        $params += [
            'model' => null,
            'headers' => [],
            'data' => [],
            'actions' => [],
            'rowActions' => [],
            'paginate' => false,
            'select' => false,
            'sortable' => false,
        ];

        // model context
        $this->modelClass = $params['model'];
        if ($this->modelClass) {
            $this->model = TableRegistry::get($this->modelClass);
        }

        // data
        if (is_object($params['data'])) {
            //$params['data'] = $params['data']->toArray();
        }

        $data =& $params['data'];

        // headers
        if (!$params['headers']) {

            if ($data instanceof CollectionInterface) {
                $firstRow = $data->first();
            } else {
                $firstRow = (is_array($data) && !empty($data) && $data[0]) ? $data[0] : [];

            }
            $firstRow = is_object($firstRow) ? $firstRow->toArray() : $firstRow;
            if ($firstRow) {
                $params['headers'] = array_keys($firstRow);
            }
        }

        $this->set('dataTable', $params);
    }
}