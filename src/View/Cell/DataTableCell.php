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
        $params = array_merge([
            'model' => null,
            'headers' => [],
            'data' => [],
            'actions' => [],
            'rowActions' => [],
            'paginate' => false,
            'select' => false,
            'sortable' => false,
            'reduce' => [],
            'filter' => [],
            'viewVars' => []
        ], $params);

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

        // sortable
        if ($params['sortable'] === true) {
            $params['sortable'] = ['plugin' => 'Backend', 'controller' => 'SimpleTree', 'action' => 'treeSort', 'model' => $params['model']];
        }

        // additional view vars for the view cell
        foreach ($params['viewVars'] as $viewVar => $val) {
            $this->set($viewVar, $val);
        }
        unset($params['viewVars']);
        
        $this->set('dataTable', $params);
    }
}