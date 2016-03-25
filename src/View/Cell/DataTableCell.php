<?php
namespace Backend\View\Cell;


use Cake\ORM\TableRegistry;
use Cake\View\Cell;

class DataTableCell extends Cell
{
    public $modelClass;

    public $model;

    public function display($params = [])
    {
        $params += ['model' => null, 'headers' => [],  'data' => [], 'actions' => [], 'rowActions' => [], 'paginate' => false];

        // model context
        $this->modelClass = $params['model'];
        if ($this->modelClass) {
            $this->model = TableRegistry::get($this->modelClass);
        }

        // data
        if (is_object($params['data'])) {
            $params['data'] = $params['data']->toArray();
        }

        // headers
        if (!$params['headers'] && isset($params['data'][0])) {
            $firstRow = is_object($params['data'][0]) ? $params['data'][0]->toArray() : $params['data'][0];
            $params['headers'] = array_keys($firstRow);
        }

        $this->set(compact('params'));
    }
}