<?php
declare(strict_types=1);

namespace Backend\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

class DataTableCell extends Cell
{
    public $modelClass;

    public $model;

    /**
     * @param array $params Cell params
     * @param array $data Data
     * @return void
     */
    public function display($params = [], $data = [])
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
            'viewVars' => [],
        ], $params);

        // model context
        $this->modelClass = $params['model'];
        if ($this->modelClass) {
            $this->model = TableRegistry::getTableLocator()->get($this->modelClass);
        }

        // data
        if ($params['data']) {
            $data = $params['data'];
        }
        if (is_object($data)) {
            //$data = $data->toArray();
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
        $this->set('data', $data);
    }
}
