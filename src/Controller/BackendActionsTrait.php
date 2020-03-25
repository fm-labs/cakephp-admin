<?php
declare(strict_types=1);

namespace Backend\Controller;

//use Cake\Controller\Exception\MissingActionException;

/**
 * Class BackendActionsTrait
 *
 * Enables Backend Actions by overriding the controllers 'invokeAction' method
 * Requires BackendComponent loaded in the controller
 *
 * @package Backend\Controller
 * @property \Backend\Controller\Component\BackendComponent $Backend
 *
 * @deprecated
 */
trait BackendActionsTrait
{
    /**
     * @return null|\Cake\Http\Response
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
