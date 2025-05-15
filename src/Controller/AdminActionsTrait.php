<?php
declare(strict_types=1);

namespace Admin\Controller;

//use Cake\Controller\Exception\MissingActionException;

/**
 * Class AdminActionsTrait
 *
 * Enables Admin Actions by overriding the controllers 'invokeAction' method
 * Requires AdminComponent loaded in the controller
 *
 * @package Admin\Controller
 * @property \Admin\Controller\Component\AdminComponent $Admin
 * @deprecated
 */
trait AdminActionsTrait
{
    /**
     * @return \Cake\Http\Response|null
     */
    public function invokeAction()
    {
        return parent::invokeAction();
        /*
        try {
            return parent::invokeAction();
        } catch (MissingActionException $ex) {
            $action = $this->request->getParam('action');

            if (!$this->Admin->hasAction($action)) {
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
