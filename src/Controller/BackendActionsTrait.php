<?php

namespace Backend\Controller;

use Cake\Controller\Exception\MissingActionException;

/**
 * Class BackendActionsTrait
 *
 * Enables Backend Actions by overriding the controllers 'invokeAction' method
 * Requires BackendComponent loaded in the controller
 *
 * @package Backend\Controller
 *
 * @TODO Remove hard dependency on BackendComponent
 */
trait BackendActionsTrait
{

    public function invokeAction()
    {
        try {
            return parent::invokeAction();

        } catch (MissingActionException $ex) {

            $action = $this->request->params['action'];

            if (!$this->Backend->hasAction($action)) {
                throw new MissingActionException([
                    'controller' => $this->name . "Controller",
                    'action' => $this->request->params['action'],
                    'prefix' => isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '',
                    'plugin' => $this->request->params['plugin'],
                ]);
            }

            $this->Backend->executeAction($action);
        }
    }
}