<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cupcake\Model\TableInputSchema;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Table;
use Cake\Routing\Router;

class ModelController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        //Configure::write('debug', 0);
        //$this->loadComponent('RequestHandler');

        $this->Auth->allow();
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->response = $this->response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'POST, GET, OPTIONS')
            ->withHeader('Access-Control-Allow-Methods', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, content-type');
    }

    public function index()
    {
        $this->viewBuilder()->setLayout(false);
    }

    public function view()
    {
        $this->viewBuilder()->setClassName('Json');
        //$this->autoRender = false;

        $modelName = $this->request->getQuery('model');
        $id = $this->request->getQuery('id');

        if (!$modelName) {
            throw new \InvalidArgumentException("Model name missing");
        }

        //$modelName = 'User.Users';
        $Model = $this->loadModel($modelName);
        if (!$Model) {
            throw new NotFoundException("Model not found");
        }

        if ($Model instanceof Table) {
            $typeMap = [
                'integer' => 'Number',
                'string' => 'Text',
                'text' => 'Textarea',
                'date' => 'Date',
                'datetime' => 'Datetime',
                'timestamp' => 'Text',
                'boolean' => 'Checkbox',
            ];

            $schema = $data = [];
            $inputSchema = new TableInputSchema();
            if (!$Model->hasBehavior('InputSchema')) {
                $Model->addBehavior('Cupcake.InputSchema');
            }
            $inputSchema = $Model->inputs();
            /*
            foreach ($Model->getSchema()->columns() as $col) {
                $schema[$col] = $Model->getSchema()->getColumn($col);

                if (!$inputSchema->field($col)) {
                    $type = (isset($typeMap[$schema[$col]['type']])) ? $typeMap[$schema[$col]['type']] : 'Text';
                    $inputSchema->addField($col, [
                        'type' => $type,
                        'label' => Inflector::humanize($col),
                    ]);
                }
            }
            */

            if ($id) {
                $entity = $Model->get($id);
                $data = $entity->toArray();
            }

            $form = [
                'ajax' => true,
                'action' => Router::url(['action' => 'edit', 'model' => $modelName, 'id' => $id], true),
                'schema' => $inputSchema->fields(),
                'data' => $data,
            ];
            $this->set('form', $form);
        }

        //$this->RequestHandler->prefers('application/json');
        //$this->RequestHandler->renderAs($this, 'json');
        //$this->response->type('application/json');
        $this->set('_serialize', 'form');
    }

    public function edit()
    {
        $this->viewBuilder()->setClassName('Json');
        //$this->autoRender = false;

        $modelName = $this->request->getQuery('model');
        $id = $this->request->getQuery('id');

        $data = $errors = [];
        try {
            if (!$modelName) {
                throw new \InvalidArgumentException("Model name missing");
            }

            $Model = $this->loadModel($modelName);
            if (!$Model) {
                throw new NotFoundException("Model not found");
            }

            if (!$id) {
                throw new \InvalidArgumentException("ID missing");
            }

            $entity = $Model->get($id);

            $entity = $Model->patchEntity($entity, $this->request->getData(), ['validate' => 'default']);
            if (!$entity->getErrors() && $Model->save($entity)) {
                $entity = $Model->save($entity);
                $data = $entity->toArray();
                $success = true;
            } else {
                $errors = $entity->getErrors();
                $success = false;
            }
        } catch (\Exception $ex) {
            $errors = ['submit' => $ex->getMessage()];
            $success = false;
        }

        $response = [
            'success' => $success,
            'request' => $this->request->getData(),
            'data' => $data,
            'errors' => $errors,
        ];

        $this->set('response', $response);
        $this->set('_serialize', 'response');
    }
}
