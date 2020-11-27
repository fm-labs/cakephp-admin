<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Exception\MissingPluginException;
use Cake\Event\EventInterface;
use Settings\Form\SettingsForm;
use Settings\Settings\SettingsSchema;

/**
 * Class SettingsController
 *
 * @package Admin\Controller\Admin
 */
class SettingsController extends AppController
{
    public $modelClass = "Settings.Settings";

    /**
     * @throws \Exception
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        if (!\Cake\Core\Plugin::isLoaded('Settings')) {
            throw new MissingPluginException(['plugin' => 'Settings']);
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * @return void
     */
    public function index(): void
    {
        /*
        $schema = new SettingsSchema();
        $schema->load('Admin.settings');

        $form = new SettingsForm($schema);
        */
        $form = null;

        if ($this->getRequest()->is(['post'])) {
            if ($this->Settings->addAll($this->getRequest()->getData())) {
                $this->Flash->success("Settings updated");
            } else {
                $this->Flash->error("Failed to update settings");
            }
        }
    }
}
