<?php

namespace Backend\Model\Behavior;

use Cake\Collection\Collection;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;

class JsTreeBehavior extends Behavior
{
    protected $_defaultConfig = [
        'fields' => [
            'id' => null,
            'title' => null,
        ],
        'dataFields' => [],
        'implementedMethods' => [],
        'implementedFinders' => [
            'jstree' => 'findJsTree',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        if (!$this->_config['fields']['id']) {
            $this->_config['fields']['id'] = $this->_table->getPrimaryKey();
        }
        if (!$this->_config['fields']['title']) {
            $this->_config['fields']['title'] = $this->_table->getDisplayField();
        }
    }

    /**
     * @param Query $query The query object
     * @param array $options Finder options
     * @return Query
     */
    public function findJsTree(Query $query, array $options = [])
    {
        return $this->formatJsTree($query, $options);
    }

    /**
     * Formats query as a flat list of sitemap locations
     *
     * @param \Cake\ORM\Query $query The query object to format.
     * @param array $options Array of options as described above.
     * @return \Cake\ORM\Query Augmented query.
     */
    public function formatJsTree(Query $query, array $options = [])
    {
        $query->find('threaded');
        $query->order(['lft' => 'ASC']);

        return $query->formatResults(function ($results) use ($options) {
            $jstree = $this->_format($results);

            return new Collection($jstree);
        });
    }

    /**
     * @param array $entities List of result entities
     * @return array
     */
    protected function _format($entities)
    {
        $this->_table->getDisplayField();

        $fields = $this->getConfig('fields');
        $dataFields = $this->getConfig('dataFields');
        if ($dataFields !== true) {
            $treeFields = ['lft' => null, 'rght' => null, 'parent_id' => null, 'level' => null];
            $dataFields = $fields + $treeFields + array_flip($dataFields);
            $dataFields = array_keys($dataFields);
        }

        $entityFormatter = function (EntityInterface $entity) use ($fields, $dataFields) {

            $class = "";
            //$publishedClass = ($entity->isPagePublished()) ? 'published' : 'unpublished';
            //$class = $entity->getPageType();
            //$class.= " " . $publishedClass;

            $id = $entity->get($fields['id']);
            $title = $entity->get($fields['title']);

            return [
                'id' => $id,
                'text' => $title,
                'data' => ($dataFields === true) ? $entity->toArray() : $entity->extract($dataFields),
                'children' => [],
                //'icon' => $class,
                /*
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                ],
                */
                //'li_attr' => ['class' => $class],
                //'a_attr' => [],
            ];
        };

        $entitiesFormatter = function ($entities) use ($entityFormatter, &$entitiesFormatter) {
            $formatted = [];
            foreach ($entities as $entity) {
                $_node = $entityFormatter($entity);
                if ($entity->children) {
                    $_node['children'] = $entitiesFormatter($entity->children);
                }
                //if ($entity->getPageChildren()) {
                //    $_node['children'] = $entitiesFormatter($entity->getPageChildren());
                //}
                $formatted[] = $_node;
            }

            return $formatted;
        };

        $entitiesFormatted = $entitiesFormatter($entities);

        return $entitiesFormatted;
    }
}
