<?php

namespace Backend\Controller\Admin;


use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;

class EntityController extends AppController
{
    public function index()
    {
        //@TODO List available tables
    }

    public function view($modelName = null, $modelId = null)
    {
        if (!$modelName || !$modelId) {
            throw new BadRequestException('Modelname or Model ID missing');
        }

        $entity = $exception = null;
        try {

            $Model = $this->loadModel($modelName);
            $query = $Model->find()->where([$Model->alias() . '.id' => $modelId]);

            //if ($Model->behaviors()->has('Media')) {
            //    $query->find('media');
            //}

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

        } catch (\Exception $ex) {
            $exception = $ex;
        }


        $this->set('modelName', $modelName);
        $this->set('modelId', $modelId);
        $this->set('entity', $entity);
        $this->set('exception', $exception);
    }

}