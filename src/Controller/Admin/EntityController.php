<?php

namespace Backend\Controller\Admin;

use Banana\Form\EntityForm;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Class EntityController
 *
 * @package Backend\Controller\Admin
 */
class EntityController extends AppController
{
    /**
     * Index method
     */
    public function index()
    {
        //@TODO List available tables
    }

    /**
     * @param null $modelName
     * @param null $modelId
     */
    public function edit($modelName = null, $modelId = null)
    {
        if (!$modelName || !$modelId) {
            throw new BadRequestException('Modelname or Model ID missing');
        }

        $entity = $exception = null;
        try {
            $Model = $this->loadModel($modelName);
            $query = $Model->find()->where([$Model->alias() . '.id' => $modelId]);

            $entity = $query->firstOrFail();

            $form = new EntityForm($entity);

            $this->set('modelName', $modelName);
            $this->set('modelId', $modelId);
            $this->set('entity', $entity);
            $this->set('exception', $exception);
            $this->set('form', $form);
        } catch (\Exception $ex) {
            $this->Flash->error($modelName . ":" . $ex->getMessage());
        }
    }

    /**
     * View method
     *
     * @param null $modelName
     * @param null $modelId
     */
    public function view($modelName = null, $modelId = null)
    {
        if (!$modelName || !$modelId) {
            throw new BadRequestException('Modelname or Model ID missing');
        }

        $entity = $exception = null;
        try {
            //$modelName = pluginSplit($modelName);

            $Model = $this->loadModel($modelName);
            $query = $Model->find()->where([$Model->alias() . '.id' => $modelId]);

            //if ($Model->behaviors()->has('Media')) {
            //    $query->find('media');
            //}

            /*
            if (!$Model->behaviors()->has('Attributes')) {
                $Model->behaviors()->load('Eav.Attributes');
            }

            $query->find('attributes');

            $entity = $query->first();
            if (!$entity) {
                throw new NotFoundException();
            }

            // inject attribute set id for debugging
            $entity->eav_attribute_set_id = 2;

            $this->set('attributes', $Model->getAttributes($entity)->toArray());
            $this->set('attributesAvailable', $Model->getAttributesAvailable($entity)->toArray());
            */

            $entity = $query->first();
            if (!$entity) {
                throw new NotFoundException();
            }
        } catch (\Exception $ex) {
            $exception = $ex;
        }

        $this->set('modelName', $modelName);
        $this->set('modelId', $modelId);
        $this->set('entity', $entity);
        $this->set('exception', $exception);
    }
}
