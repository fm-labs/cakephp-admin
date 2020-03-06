<?php

namespace Backend\Controller;

use Backend\Controller\Component\BackendComponent;
//use Cake\Controller\Exception\MissingActionException;
use Cake\Http\Response;

/**
 * Class BackendActionsTrait
 *
 * Enables Backend Actions by overriding the controllers 'invokeAction' method
 * Requires BackendComponent loaded in the controller
 *
 * @package Backend\Controller
 * @property BackendComponent $Backend
 *
 * @deprecated
 */
trait BackendActionsTrait
{
    /**
     * @return null|Response
     */
    public function invokeAction()
    {
        return parent::invokeAction();
        /*
        try {
            return parent::invokeAction();
        } catch (MissingActionException $ex) {
            $action = $this->request->getParam('action');

            if (!$this->Backend->hasAction($action)) {
                throw new MissingActionException([
                    'controller' => $this->name . "Controller",
                    'action' => $this->request->getParam('action'),
                    'prefix' => isset($this->request->getParam('prefix')) ? $this->request->getParam('prefix') : '',
                    'plugin' => $this->request->getParam('plugin'),
                ]);
            }

            return $this->Action->execute($action);
        }
        */
    }
}
